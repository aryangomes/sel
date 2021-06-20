<?php

namespace App\Traits;

trait HasPermission
{

    public function canMadeThisAction($action)
    {

        if ($this->isAdmin) {
            return true;
        }

        $canMadeThisAction = false;

        $permission = $this->searchPermission($action);

        if ($permission != null) {
            $canMadeThisAction = $permission->can;
        }


        return $canMadeThisAction;
    }

    public function searchPermission($action)
    {
        $permission = null;

        $searchPermission = $this->profile->permissions
            ->where('permission.permission', $action)->first();

        if ($searchPermission != null) {
            $permission = $searchPermission->permission;
        }

        return $permission;
    }
}
