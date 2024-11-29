<?php

namespace App\Policies;

use App\Models\Error;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ErrorPolicy
{
    public function show(User $user, Error $error): Response
    {
        return $this->canAccessError($user, $error)
            ? Response::allow()
            : Response::deny('No tienes autorización para ver este dato');
    }

    public function update(User $user, Error $error): Response
    {
        return $this->canAccessError($user, $error)
            ? Response::allow()
            : Response::deny('No tienes autorización para actualizar este dato');
    }

    public function destroy(User $user, Error $error): Response
    {
        return $this->canAccessError($user, $error)
            ? Response::allow()
            : Response::deny('No tienes autorización para borrar este dato');
    }

    /**
     *
     *
     * @param User $user
     * @param Error $error
     * @return bool
     */
    protected function canAccessError(User $user, Error $error): bool
    {
        // Verifica si el usuario es el propietario del error o un colaborador del propietario.
        return $user->id === $error->user_id || $user->main_user_id === $error->user_id;
    }
}
