<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClimaController extends Controller
{
    public function __invoke(Request $request)
    {
        $datos = $request->validate([
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        // ajusta estas claves a tu config/manovecina.php
        $lat = $datos['lat'] ?? config('manovecina.centro.lat', 19.4326);
        $lng = $datos['lng'] ?? config('manovecina.centro.lng', -99.1332);

        try {
            $respuesta = Http::timeout(5)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => $lat,
                'longitude' => $lng,
                'current' => 'temperature_2m,precipitation,wind_speed_10m,weather_code',
            ]);
        } catch (ConnectionException) {
            return response()->json(['message' => 'Servicio de clima no disponible.'], 503);
        }

        $actual = $respuesta->json('current');

        if ($respuesta->failed() || ! $actual) {
            return response()->json(['message' => 'Servicio de clima no disponible.'], 503);
        }

        $codigo = (int) $actual['weather_code'];
        $lluvia = in_array($codigo, [51, 53, 55, 56, 57, 61, 63, 65, 66, 67, 80, 81, 82, 95, 96, 99], true)
            || $actual['precipitation'] > 0;

        return response()->json([
            'temperatura' => $actual['temperature_2m'],
            'precipitacion_mm' => $actual['precipitation'],
            'viento_kmh' => $actual['wind_speed_10m'],
            'descripcion' => $this->descripcion($codigo),
            'recomendacion' => $lluvia
                ? 'Hay lluvia: no uses herramientas eléctricas al aire libre.'
                : 'Buen momento para usar herramientas al aire libre.',
            'fuente' => 'Open-Meteo',
        ]);
    }

    private function descripcion(int $codigo): string
    {
        return match (true) {
            $codigo === 0 => 'Despejado',
            $codigo <= 3 => 'Parcialmente nublado',
            in_array($codigo, [45, 48]) => 'Niebla',
            ($codigo >= 51 && $codigo <= 67) || ($codigo >= 80 && $codigo <= 82) => 'Lluvia',
            $codigo >= 71 && $codigo <= 77 => 'Nieve',
            $codigo >= 95 => 'Tormenta',
            default => 'Nublado',
        };
    }
}
