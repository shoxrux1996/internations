<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Group;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Admin  $admin
     * @param  \App\Models\Group  $group
     * @return mixed
     */
    public function delete(Admin $admin, Group $group)
    {
        return $group->users()->exists()
            ? Response::deny('Cant delete group, because it has members', 422)
            : Response::allow();
    }
}
