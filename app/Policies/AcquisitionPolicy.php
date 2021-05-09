<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Model\Acquisition;

class AcquisitionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any acquisitions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the acquisition.
     *
     * @param  \App\Models\User  $user
     * @param  \Model\Acquisition  $acquisition
     * @return mixed
     */
    public function view(User $user, Acquisition $acquisition)
    {
        //
    }

    /**
     * Determine whether the user can create acquisitions.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the acquisition.
     *
     * @param  \App\Models\User  $user
     * @param  \Model\Acquisition  $acquisition
     * @return mixed
     */
    public function update(User $user, Acquisition $acquisition)
    {
        //
    }

    /**
     * Determine whether the user can delete the acquisition.
     *
     * @param  \App\Models\User  $user
     * @param  \Model\Acquisition  $acquisition
     * @return mixed
     */
    public function delete(User $user, Acquisition $acquisition)
    {
        //
    }

    /**
     * Determine whether the user can restore the acquisition.
     *
     * @param  \App\Models\User  $user
     * @param  \Model\Acquisition  $acquisition
     * @return mixed
     */
    public function restore(User $user, Acquisition $acquisition)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the acquisition.
     *
     * @param  \App\Models\User  $user
     * @param  \Model\Acquisition  $acquisition
     * @return mixed
     */
    public function forceDelete(User $user, Acquisition $acquisition)
    {
        //
    }
}
