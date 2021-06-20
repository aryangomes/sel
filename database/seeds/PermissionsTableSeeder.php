<?php

use App\Http\Controllers\Api\v1\AcquisitionController;
use App\Models\Acquisition;
use App\Models\Utils\DbTables;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                AcquisitionPermissionsSeeder::class
            ]
        );
    }
}
