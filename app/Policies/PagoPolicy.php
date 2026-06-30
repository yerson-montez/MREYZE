<?php

namespace App\Policies;

use App\Models\Pago;
use App\Models\User;

class PagoPolicy
{
    public function view(User $user, Pago $pago): bool
    {
        return $user->id === $pago->user_id || $user->esAdmin();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function approve(User $user): bool
    {
        return $user->esAdmin();
    }
}
