<?php

namespace App\Policies;

use App\Models\Herramienta;
use App\Models\User;

class HerramientaPolicy
{
    public function update(User $user, Herramienta $herramienta): bool
    {
        return $herramienta->user_id === $user->id;
    }
}
