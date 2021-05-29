<?php

namespace App\Policies;

use App\Models\CollectionCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any collection categories.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can view the collection category.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\CollectionCategory  $collectionCategory
     * @return mixed
     */
    public function view(User $user, CollectionCategory $collectionCategory)
    {
        //
    }

    /**
     * Determine whether the user can create collection categories.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the collection category.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\CollectionCategory  $collectionCategory
     * @return mixed
     */
    public function update(User $user, CollectionCategory $collectionCategory)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the collection category.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\CollectionCategory  $collectionCategory
     * @return mixed
     */
    public function delete(User $user, CollectionCategory $collectionCategory)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the collection category.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\CollectionCategory  $collectionCategory
     * @return mixed
     */
    public function restore(User $user, CollectionCategory $collectionCategory)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the collection category.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\CollectionCategory  $collectionCategory
     * @return mixed
     */
    public function forceDelete(User $user, CollectionCategory $collectionCategory)
    {
        //
    }
}
