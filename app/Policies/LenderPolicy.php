<?php

namespace App\Policies;

use App\Models\Lender;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LenderPolicy extends BasePolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view any lenders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the lender.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lender  $lender
     * @return mixed
     */
    public function view(User $user, Lender $lender)
    {
        return true;
    }

    /**
     * Determine whether the user can create lenders.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the lender.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lender  $lender
     * @return mixed
     */
    public function update(User $user, Lender $lender)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the lender.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lender  $lender
     * @return mixed
     */
    public function delete(User $user, Lender $lender)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the lender.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lender  $lender
     * @return mixed
     */
    public function restore(User $user, Lender $lender)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the lender.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Lender  $lender
     * @return mixed
     */
    public function forceDelete(User $user, Lender $lender)
    {
        //
    }

    private function userIsAdmin($user)
    {
        return ($user->isAdmin)?Response::allow():Response::deny('User should Administrator to do this action.');
    }
}
