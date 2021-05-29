<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Collection;

class CollectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any collections.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can view the collection.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\Collection  $collection
     * @return mixed
     */
    public function view(User $user, Collection $collection)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can create collections.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the collection.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\Collection  $collection
     * @return mixed
     */
    public function update(User $user, Collection $collection)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the collection.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\Collection  $collection
     * @return mixed
     */
    public function delete(User $user, Collection $collection)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the collection.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\Collection  $collection
     * @return mixed
     */
    public function restore(User $user, Collection $collection)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the collection.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\Collection  $collection
     * @return mixed
     */
    public function forceDelete(User $user, Collection $collection)
    {
        //
    }
}
