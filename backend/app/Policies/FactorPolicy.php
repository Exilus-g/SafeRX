<?php

namespace App\Policies;

use App\Models\Factor;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FactorPolicy
{
    public function show(User $user, Factor $factor): Response
    {
        return $user->id === $factor->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para ver este dato');
    }

    public function update(User $user, Factor $factor): Response
    {
        return $user->id === $factor->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para actualizar este dato');
    }

    public function destroy(User $user, Factor $factor): Response
    {
        return $user->id === $factor->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para borrar este dato');
    }
}
