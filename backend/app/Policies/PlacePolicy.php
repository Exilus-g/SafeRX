<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlacePolicy
{

    public function show(User $user, Place $place): Response
    {
        return $user->id === $place->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para ver este dato');
    }

    public function update(User $user, Place $place): Response
    {
        return $user->id === $place->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para actualizar este dato');
    }

    public function destroy(User $user, Place $place): Response
    {
        return $user->id === $place->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para borrar este dato');
    }
}
