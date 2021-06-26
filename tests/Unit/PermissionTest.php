<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class PermissionTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    private $urlPermission;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlPermission = "{$this->url}permissions";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterPermissionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postPermission = factory(Permission::class)->make()->toArray();

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlPermission, $postPermission);

        $response->assertCreated();
    }

    public function testRegisterPermissionFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postPermission = factory(Permission::class)->make(
            [
                'permission' => $this->faker->randomDigit,
                'idProfile' => factory(Profile::class),

            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlPermission, $postPermission);

        $response->assertStatus(422);
    }

    public function testUpdatePermissionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $permission = factory(Permission::class)->create();

        $dataUpdateForPermission = [
            'permission' => $this->faker->lexify('can.?????'),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlPermission, $permission->idPermission),
            $dataUpdateForPermission
        );



        $response->assertOk();
        $getPermission = $response->getData()->permission;

        $this->assertEquals(
            $getPermission->permission,
            $dataUpdateForPermission['permission']
        );
    }

    public function testUpdatePermissionFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $permission = factory(Permission::class)->create();

        $dataUpdateForPermission = [
            'permission' => $this->faker->randomDigit,
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlPermission, $permission->idPermission),
            $dataUpdateForPermission
        );

        $response->assertStatus(422);

        $response = $this->getJson(
            $this->urlWithParameter($this->urlPermission, $permission->idPermission)
        );

        $this->assertNotEquals(
            $response->getData()->data->permission,
            $dataUpdateForPermission['permission']
        );
    }

    public function testViewPermissionDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $permission = factory(Permission::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlPermission, $permission->idPermission)
        );

        $response->assertOk();


        $user = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlPermission, $permission->idPermission)
        );

        $response->assertOk();
    }

    public function testDeletePermissionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $permission = factory(Permission::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlPermission, $permission->idPermission)
        );

        $response->assertOk();

        $permissionWasDeleted = isset(Permission::withTrashed()->find($permission->idPermission)->deleted_at);

        $this->assertTrue($permissionWasDeleted);
    }

    public function testUserNotAdminTruingDeletePermissionUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $permission = factory(Permission::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlPermission, $permission->idPermission)
        );

        $response->assertForbidden();
    }
}
