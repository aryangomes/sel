<?php

namespace Tests\Feature;

use App\Models\Acquisition;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileHasPermission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;
use Laravel\Passport\Passport;

class PermissionsAllowActionTest extends BaseTest
{
    use RefreshDatabase, WithFaker;


    /**
     * @override
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testUserAdminStoreAcquisitionSuccessfully()
    {
        $this->createAndAuthenticateTheAdminUser();

        $postAcquisition = factory(Acquisition::class)->make()->toArray();


        Passport::actingAs($this->userAdmin);
        $this->assertAuthenticatedAs($this->userAdmin, 'api');

        $response = $this->postJson("{$this->url}acquisitions", $postAcquisition);

        $response->assertCreated();
    }

    public function testUserNotAdminStoreAcquisitionUnsuccessfully()
    {

        $profile = factory(Profile::class)->create();
        $permission = factory(Permission::class)->create(
            [
                'permission' => 'acquisitions-store'
            ]
        );

        $profileHasAcquisitions = factory(ProfileHasPermission::class)->create(
            [
                'idProfile' => $profile,
                'idPermission' => $permission,
                'can' => false,
            ]
        );
        $this->createAndAuthenticateTheUserNotAdmin(
            [
                'idProfile' => $profile
            ]
        );



        $postAcquisition = factory(Acquisition::class)->make()->toArray();


        Passport::actingAs($this->userNotAdmin);
        $this->assertAuthenticatedAs($this->userNotAdmin, 'api');

        $response = $this->postJson("{$this->url}acquisitions", $postAcquisition);

        $response->assertForbidden();
    }

    public function testUserNotAdminStoreAcquisitionSuccessfully()
    {

        $profile = factory(Profile::class)->create();
        $permission = factory(Permission::class)->create(
            [
                'permission' => 'acquisitions-store'
            ]
        );

        $profileHasAcquisitions = factory(ProfileHasPermission::class)->create(
            [
                'idProfile' => $profile,
                'idPermission' => $permission,
                'can' => true,
            ]
        );
        $this->createAndAuthenticateTheUserNotAdmin(
            [
                'idProfile' => $profile,
             
            ]
        );

        $postAcquisition = factory(Acquisition::class)->make()->toArray();


        $response = $this->postJson("{$this->url}acquisitions", $postAcquisition);

        $response->assertCreated();
    }

    public function testUserNotAdminWithNoPermissionToStoreAcquisition()
    {

        $profile = factory(Profile::class)->create();
        $permission = factory(Permission::class)->create();

        $profileHasAcquisitions = factory(ProfileHasPermission::class)->create(
            [
                'idProfile' => $profile,
                'idPermission' => $permission,
                'can' => false,
            ]
        );
        $this->createAndAuthenticateTheUserNotAdmin(
            [
                'idProfile' => $profile,
               
            ]
        );

        $postAcquisition = factory(Acquisition::class)->make()->toArray();


        $response = $this->postJson("{$this->url}acquisitions", $postAcquisition);

        $response->assertForbidden();
    }

    /* public function testUserNotAdminWithNoPermissionToDoAction()
    {

        $profile = factory(Profile::class)->create();
        $permission = factory(Permission::class)->create(
            [
                'permission'=>'acquisitions-test'
            ]
        );

        $profileHasAcquisitions = factory(ProfileHasPermission::class)->create(
            [
                'idProfile' => $profile,
                'idPermission' => $permission,
                'can' => false,
            ]
        );
        $this->createAndAuthenticateTheUserNotAdmin(
            [
                'idProfile' => $profile,
             
            ]
        );

        $response = $this->get("{$this->url}acquisitions/teste");

        $response->assertForbidden();
    }

    public function testUserNotAdminWithPermissionToDoAction()
    {

        $profile = factory(Profile::class)->create();
        $permission = factory(Permission::class)->create(
            [
                'permission'=>'acquisitions-test'
            ]
        );

        $profileHasAcquisitions = factory(ProfileHasPermission::class)->create(
            [
                'idProfile' => $profile,
                'idPermission' => $permission,
                'can' => true,
            ]
        );
        $this->createAndAuthenticateTheUserNotAdmin(
            [
                'idProfile' => $profile,
             
            ]
        );

        $response = $this->get("{$this->url}acquisitions/teste");

        $response->assertOk();
    } */
}
