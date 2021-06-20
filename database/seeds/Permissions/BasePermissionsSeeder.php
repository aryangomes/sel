<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BasePermissionsSeeder extends Seeder
{

    protected $tableName;

    protected $permissionsGenerated = [];

    protected const CRUD_ACTIONS = [
        'index',
        'view',
        'create',
        'store',
        'edit',
        'update',
        'delete',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    }

    protected function generatePermissions()
    {
        $crudActions = collect($this::CRUD_ACTIONS);

        $generatePermissions = $crudActions->map(function ($item, $key) {
            $permission = $this->generatePermissionName(
                [$this->tableName, $item]
            );
            return $permission;
        });

        logger(
            get_class($this),
            [
                'generatePermissions' => $generatePermissions
            ]
        );

        return $generatePermissions;
    }

    public function generatePermissionName(array $arguments)
    {

        $argumentsCollection = collect($arguments);

        $generatePermissionName = Str::slug($argumentsCollection->implode(' ', ' '));

        return $generatePermissionName;
    }

    protected function insertPermissionInDatabase($permission)
    {
        DB::table('permissions')->insert($permission);
    }
}
