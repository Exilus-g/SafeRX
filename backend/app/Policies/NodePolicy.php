<?php

namespace App\Policies;

use App\Models\Node;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NodePolicy
{

    public function show(User $user, Node $node): Response
    {
        return $user->id === $node->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para ver este dato');
    }

    public function update(User $user, Node $node): Response
    {
        return $user->id === $node->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para actualizar este dato');
    }

    public function destroy(User $user, Node $node): Response
    {
        return $user->id === $node->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para borrar este dato');
    }
}
