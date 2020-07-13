<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use HandlesAuthorization;

    private $userAuthenticated;
    private $user;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $userAuthenticated
     * @return mixed
     */
    public function viewAny(User $userAuthenticated)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $userAuthenticated
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $userAuthenticated, User $user)
    {

        $this->user = $user;
        $this->userAuthenticated = $userAuthenticated;

        return $this->validateAction();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $userAuthenticated
     * @return mixed
     */
    public function create(User $userAuthenticated)
    {
        return $userAuthenticated->isAdmin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $userAuthenticated
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function update(User $userAuthenticated, User $user)
    {
        $this->user = $user;
        $this->userAuthenticated = $userAuthenticated;

        return $this->validateAction();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $userAuthenticated
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function delete(User $userAuthenticated, User $user)
    {
        $this->user = $user;
        $this->userAuthenticated = $userAuthenticated;

        return $this->validateAction();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $userAuthenticated
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function restore(User $userAuthenticated, User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $userAuthenticated
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function forceDelete(User $userAuthenticated, User $user)
    {
        return false;
    }

    private function validateAction(
        $denyMessage = 'This User do not authorized to do this action.'
    ) {

        $validateAction = Response::deny($denyMessage);

        $userAuthenticadedIsRelationedWithTheUser =
            ($this->user->id == $this->userAuthenticated->id);

        if ($userAuthenticadedIsRelationedWithTheUser) {
            $validateAction = Response::allow();
        }

        return $validateAction;
    }
}
