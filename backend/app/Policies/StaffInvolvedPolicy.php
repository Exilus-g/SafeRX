<?php

namespace App\Policies;

use App\Models\StaffInvolved;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StaffInvolvedPolicy
{
    public function show(User $user, StaffInvolved $staffInvolved): Response
    {
        return $user->id === $staffInvolved->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para ver este dato');
    }

    public function update(User $user, StaffInvolved $staffInvolved): Response
    {
        return $user->id === $staffInvolved->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para actualizar este dato');
    }

    public function destroy(User $user, StaffInvolved $staffInvolved): Response
    {
        return $user->id === $staffInvolved->user_id
            ? Response::allow()
            : Response::deny('No tienes autorización para borrar este dato');
    }
}
