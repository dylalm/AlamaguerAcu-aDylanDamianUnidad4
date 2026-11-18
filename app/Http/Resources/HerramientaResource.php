<?php

namespace App\Http\Resources;

use App\Models\Herramienta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Formato JSON de una herramienta en la API. */
class HerramientaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'barrio' => $this->barrio,
            'latitud' => (float) $this->latitud,
            'longitud' => (float) $this->longitud,
            'disponible' => $this->disponible,
            'propietario' => $this->propietario?->name,
        ];
    }
}
