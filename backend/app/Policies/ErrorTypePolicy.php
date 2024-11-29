<?php

namespace App\Policies;

use App\Models\ErrorType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ErrorTypePolicy
{
    public function show(User $user, ErrorType $errorType): Response
    {
        return $user->id === $errorType->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para ver este dato');
    }

    public function update(User $user, ErrorType $errorType): Response
    {
        return $user->id === $errorType->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para actualizar este dato');
    }

    public function destroy(User $user, ErrorType $errorType): Response
    {
        return $user->id === $errorType->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para borrar este dato');
    }
}
