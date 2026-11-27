<?php

namespace App\Http\Controllers;

use App\Models\Herramienta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /** Un vecino pide prestada una herramienta: queda ocupada a su nombre. */
    public function __invoke(Request $request, Herramienta $herramienta): RedirectResponse
    {
        $usuario = $request->user();

        if ($herramienta->user_id === $usuario->id) {
            return back()->withErrors(['prestamo' => 'No puedes pedir prestada tu propia herramienta.']);
        }

        if (! $herramienta->prestarA($usuario)) {
            return back()->withErrors(['prestamo' => 'Esta herramienta ya está ocupada por otro vecino.']);
        }

        return back();
    }
}
