<?php

use App\Models\Permission;

class CollectionTypePermissionsSeeder extends BasePermissionsSeeder
{

    public function __construct()
    {
        $this->tableName = 'collection_types';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->permissionsGenerated = $this->generatePermissions();

        foreach ($this->permissionsGenerated as  $permissionGenerated) {
            $permissionFactory = factory(Permission::class)->make(
                [
                    'permission' => $permissionGenerated,
                    'description' => $permissionGenerated,
                ]
            )->toArray();

            $this->insertPermissionInDatabase($permissionFactory);
        }
    }
}
