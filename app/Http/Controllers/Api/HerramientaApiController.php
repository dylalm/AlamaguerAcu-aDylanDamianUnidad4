<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HerramientaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'categoria' => $this->categoria,
            'descripcion' => $this->descripcion,
            'disponible' => $this->disponible,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'dueno' => $this->user?->name,
        ];
    }
}
