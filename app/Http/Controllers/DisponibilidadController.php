<?php

namespace App\Http\Controllers;

use App\Models\Herramienta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DisponibilidadController extends Controller
{
    public function __invoke(Request $request, Herramienta $herramienta): RedirectResponse
    {
        Gate::authorize('update', $herramienta); // 403 si no es el dueño

        $datos = $request->validate([
            'disponible' => ['sometimes', 'boolean'],
        ]);

        $herramienta->disponible = $datos['disponible'] ?? ! $herramienta->disponible;
        $herramienta->save();

        return back();
    }
}