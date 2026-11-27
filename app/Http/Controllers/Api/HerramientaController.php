<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HerramientaResource;
use App\Models\Herramienta;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class HerramientaController extends Controller
{
    // GET /api/herramientas?search=&categoria=&solo_disponibles=1
    public function index(Request $request)
    {
        $herramientas = Herramienta::query()
            ->with('propietario')
            ->when($request->query('search'), fn ($q, $s) => $q->where('nombre', 'like', "%{$s}%"))
            ->when($request->query('categoria'), fn ($q, $c) => $q->where('categoria', $c))
            ->when($request->boolean('solo_disponibles'), fn ($q) => $q->where('disponible', true))
            ->paginate(10)
            ->withQueryString();

        return HerramientaResource::collection($herramientas);
    }

    public function create()
    {
        return Inertia::render('Herramientas/Create', [
            'categorias' => collect(Herramienta::CATEGORIAS)
                ->map(fn ($etiqueta, $clave) => ['valor' => $clave, 'etiqueta' => $etiqueta])
                ->values(),
        ]);
    }

    // GET /api/herramientas/{id}
    public function show(Herramienta $herramienta)
    {
        return new HerramientaResource($herramienta->load('user'));
    }

    // PATCH /api/herramientas/{id}/disponibilidad  (requiere token)
    public function disponibilidad(Request $request, Herramienta $herramienta)
    {
        Gate::authorize('update', $herramienta);

        $datos = $request->validate(['disponible' => ['sometimes', 'boolean']]);

        $herramienta->disponible = $datos['disponible'] ?? ! $herramienta->disponible;
        $herramienta->save();

        return new HerramientaResource($herramienta->load('user'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'descripcion' => ['required', 'string', 'max:1000'],
            // copia aquí la misma lista que ya usa el HerramientaController web
            'categoria' => ['required', Rule::in(array_keys(Herramienta::CATEGORIAS))],
            'barrio' => ['required', 'string', 'max:60'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if (! isset($datos['latitud'], $datos['longitud'])) {
            $coordenadas = $this->geocodificar($datos['barrio']);

            if (! $coordenadas) {
                return response()->json([
                    'message' => 'No se encontró la zona indicada.',
                    'errors' => ['barrio' => ['No se pudo ubicar este barrio.']],
                ], 422);
            }

            [$datos['latitud'], $datos['longitud']] = $coordenadas;
        }

        $herramienta = new Herramienta($datos);
        $herramienta->user_id = $request->user()->id; // nunca del cliente
        $herramienta->disponible = true;
        $herramienta->save();

        return (new HerramientaResource($herramienta->load('propietario')))
            ->response()
            ->setStatusCode(201);
    }

    private function geocodificar(string $barrio): ?array
    {
        try {
            $respuesta = Http::withHeaders(['User-Agent' => 'ManoVecina/1.0 (proyecto escolar)'])
                ->timeout(5)
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $barrio,
                    'format' => 'json',
                    'limit' => 1,
                ]);
        } catch (ConnectionException) {
            return null;
        }

        $primero = $respuesta->json('0');

        return $primero ? [(float) $primero['lat'], (float) $primero['lon']] : null;
    }
}
