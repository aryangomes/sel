<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionAction extends Model
{
    static $COMMOM_ACTIONS = [
        'index',
        'show',
        'create',
        'store',
        'edit',
        'update',
        'destroy',
    ];
}
