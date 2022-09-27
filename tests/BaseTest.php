<?php

namespace Tests;

use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileHasPermission;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use PermissionsTableSeeder;

abstract class BaseTest extends BaseTestCase
{
    use CreatesApplication;


    public $url = 'api/v1/';

    protected $userAdmin;

    protected $userNotAdmin;

    protected $userProfile;

    public function urlWithParameter($url, $parameter = null)
    {
        $urlWithParameter = $url;

        if (isset($parameter)) {
            $urlWithParameter .= "/{$parameter}";
        }

        return $urlWithParameter;
    }

    public function printContentResponse($response)
    {
        print_r($response->getContent());
    }

    protected function createAndAuthenticateTheAdminUser()
    {
        $this->userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        Passport::actingAs($this->userAdmin);

        $this->assertAuthenticatedAs($this->userAdmin, 'api');
    }

    protected function createAndAuthenticateTheUserNotAdmin($attributes = [])
    {
        if (count($attributes) > 0) {

            $this->userNotAdmin = factory(User::class)->create($attributes);
        } else {
            $this->userNotAdmin = factory(User::class)->create();
        }



        Passport::actingAs($this->userNotAdmin);

        $this->assertAuthenticatedAs($this->userNotAdmin, 'api');
    }

    protected function generateProfilePermissions($table, $profile = null)
    {
        if ($profile == null && $this->userProfile != null) {
            $profile = $this->userProfile;
        }
        $this->seed(PermissionsTableSeeder::class);


        $permissions = Permission::tablePermissions($table)->get();

        foreach ($permissions as $permission) {
            factory(ProfileHasPermission::class)->create(
                [
                    'idProfile' => $profile,
                    'idPermission' => $permission,
                    'can' => true,
                ]
            );
        }
    }

    public function generateProfile()
    {
        $this->userProfile = factory(Profile::class)->create();
    }
}
