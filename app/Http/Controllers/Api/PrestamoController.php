<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HerramientaResource;
use App\Models\Herramienta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /** Pide prestada una herramienta a nombre del usuario del token. */
    public function __invoke(Request $request, Herramienta $herramienta): HerramientaResource|JsonResponse
    {
        $usuario = $request->user();

        if ($herramienta->user_id === $usuario->id) {
            return response()->json(['mensaje' => 'No puedes pedir prestada tu propia herramienta.'], 422);
        }

        if (! $herramienta->prestarA($usuario)) {
            return response()->json(['mensaje' => 'La herramienta ya está ocupada por otro vecino.'], 409);
        }

        return new HerramientaResource($herramienta->fresh(['propietario', 'prestatario']));
    }
}
