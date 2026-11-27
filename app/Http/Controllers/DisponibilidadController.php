<?php

namespace App\Http\Controllers;

use App\Models\Herramienta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DisponibilidadController extends Controller
{
    /** Alterna una herramienta entre «disponible» y «prestada». HerramientaPolicy exige ser el dueño. */
    public function __invoke(Request $request, Herramienta $herramienta): RedirectResponse
    {
        Gate::authorize('update', $herramienta);

        $disponible = ! $herramienta->disponible;

        // Al volver a estar disponible, ya no la tiene nadie en préstamo
        $herramienta->update([
            'disponible' => $disponible,
            'prestado_a' => $disponible ? null : $herramienta->prestado_a,
        ]);

        return back();
    }
}
