<?php

namespace App\Policies;
use App\Models\User;
use App\Models\User as Collaborator;
use Illuminate\Auth\Access\Response;

class FactorPolicy
{
    public function view(User $user, Collaborator $collaborator): Response
    {
        return $user->id === $collaborator->main_user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para ver este colaborador');
    }

    // Verificar si el usuario puede actualizar al colaborador
    public function update(User $user, Collaborator $collaborator): Response
    {
        return $user->id === $collaborator->main_user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para actualizar este colaborador');
    }

    // Verificar si el usuario puede eliminar al colaborador
    public function destroy(User $user, Collaborator $collaborator): Response
    {
        return $user->id === $collaborator->main_user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para eliminar este colaborador');
    }
}
