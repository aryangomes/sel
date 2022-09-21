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


        if ($this->profile != null) {
            $permission = $this->profile->permissions
                ->where('permission.permission', $action)->first();
        }

        return $permission;
    }
}
