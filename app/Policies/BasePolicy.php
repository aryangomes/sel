<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function canPerformAction(User $user, Model $model, string $action)
    {

        return $user->canMadeThisAction($action);
    }

    public function resourceBelongsToUser(User $user,  $idResource)
    {
        return $user->id == $idResource;
    }

    public function canPerformActionOrResourceBelongsToUser(
        User $user,
        Model $model,
        string $action,
        $idResource
    ) {

        return $this->canPerformAction($user, $model, $action) ||
            $this->resourceBelongsToUser($user, $idResource);
    }
}
