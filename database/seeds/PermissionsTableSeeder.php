<?php


use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(
            [
                AcquisitionPermissionsSeeder::class,
                AcquisitionTypePermissionsSeeder::class,
                CollectionCategoryPermissionsSeeder::class,
                CollectionPermissionsSeeder::class,
                CollectionCopyPermissionsSeeder::class,
                CollectionTypePermissionsSeeder::class,
                LoanContainsCollectionCopyPermissionsSeeder::class,
                PermissionPermissionsSeeder::class,
                ProfilePermissionsSeeder::class,
                UserPermissionsSeeder::class,
                LenderPermissionsSeeder::class,
                ProviderPermissionsSeeder::class,
            ]
        );
    }
}
