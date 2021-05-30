<?php

namespace App\Policies;

use App\Models\CollectionCopy;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionCopyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any collection copies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can view the collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return mixed
     */
    public function view(User $user, CollectionCopy $collectionCopy)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can create collection copies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return mixed
     */
    public function update(User $user, CollectionCopy $collectionCopy)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return mixed
     */
    public function delete(User $user, CollectionCopy $collectionCopy)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return mixed
     */
    public function restore(User $user, CollectionCopy $collectionCopy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the collection copy.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionCopy  $collectionCopy
     * @return mixed
     */
    public function forceDelete(User $user, CollectionCopy $collectionCopy)
    {
        //
    }
}
