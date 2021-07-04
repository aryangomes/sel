<?php

namespace App\Policies;

use App\Models\AcquisitionType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcquisitionTypePolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any acquistion types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the acquistion type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AcquisitionType  $AcquisitionType
     * @return mixed
     */
    public function view(User $user, AcquisitionType $AcquisitionType)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can create acquistion types.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can update the acquistion type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AcquisitionType  $AcquisitionType
     * @return mixed
     */
    public function update(User $user, AcquisitionType $AcquisitionType)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can delete the acquistion type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AcquisitionType  $AcquisitionType
     * @return mixed
     */
    public function delete(User $user, AcquisitionType $AcquisitionType)
    {
        return $user->mayToDoThisAction();
    }

    /**
     * Determine whether the user can restore the acquistion type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AcquisitionType  $AcquisitionType
     * @return mixed
     */
    public function restore(User $user, AcquisitionType $AcquisitionType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the acquistion type.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AcquisitionType  $AcquisitionType
     * @return mixed
     */
    public function forceDelete(User $user, AcquisitionType $AcquisitionType)
    {
        //
    }

   
}
