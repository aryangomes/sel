<?php

namespace App\Policies;

use App\Models\CollectionType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionTypePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any collection types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can view the collection type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionType  $collectionType
     * @return mixed
     */
    public function view(User $user, CollectionType $collectionType)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can create collection types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the collection type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionType  $collectionType
     * @return mixed
     */
    public function update(User $user, CollectionType $collectionType)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the collection type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionType  $collectionType
     * @return mixed
     */
    public function delete(User $user, CollectionType $collectionType)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the collection type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionType  $collectionType
     * @return mixed
     */
    public function restore(User $user, CollectionType $collectionType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the collection type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CollectionType  $collectionType
     * @return mixed
     */
    public function forceDelete(User $user, CollectionType $collectionType)
    {
        //
    }
}
