<?php

namespace App\Http\Requests;

use App\Models\Herramienta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHerramientaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80'],
            // usa aquí la MISMA lista de categorías que ya valida tu catálogo
            'categoria' => ['required', Rule::in(array_keys(Herramienta::CATEGORIAS))],
            'barrio' => ['required', 'string', 'max:60'],
            'descripcion' => ['required', 'string', 'max:1000'],
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
        ];
    }
}
