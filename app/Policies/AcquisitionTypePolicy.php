<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Models\AcquisitionType;

class AcquisitionTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any acquisition types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the acquisition type.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\AcquisitionType  $acquisitionType
     * @return mixed
     */
    public function view(User $user, AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Determine whether the user can create acquisition types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the acquisition type.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\AcquisitionType  $acquisitionType
     * @return mixed
     */
    public function update(User $user, AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Determine whether the user can delete the acquisition type.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\AcquisitionType  $acquisitionType
     * @return mixed
     */
    public function delete(User $user, AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Determine whether the user can restore the acquisition type.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\AcquisitionType  $acquisitionType
     * @return mixed
     */
    public function restore(User $user, AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the acquisition type.
     *
     * @param  \App\Models\User  $user
     * @param  \Models\AcquisitionType  $acquisitionType
     * @return mixed
     */
    public function forceDelete(User $user, AcquisitionType $acquisitionType)
    {
        //
    }
}
