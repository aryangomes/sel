<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $urlUser;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlUser = $this->url . 'user';
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterUserNotAdminSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $userPost = factory(User::class)->make()->toArray();

        $userPost['password'] = env('DEFAULT_PASSWORD_NOT_ADMIN');
        $userPost['password_confirmation'] = $userPost['password'];

        $response = $this->postJson($this->urlUser, $userPost);

        $response->assertCreated();
    }

    public function testRegisterUserNotAdminWithInvalidDatas()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $userPost = factory(User::class)->make(
            [
                'name' => null,
                'cpf' => '12345678',

            ]
        )->toArray();

        $userPost['password'] = env('DEFAULT_PASSWORD_NOT_ADMIN');
        $userPost['password_confirmation'] = $userPost['password'];

        $response = $this->postJson($this->urlUser, $userPost);

        $response->assertStatus(422);
    }

    public function testUpdateUserNotAdminSuccessfully()
    {
        $user = factory(User::class)->create();

        $dataUpdateForUser = [
            'name' => 'New Name'
        ];

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlUser, $user->id),
            $dataUpdateForUser
        );

        $response->assertOk();

        $userResponse = $response->getData()->user;

        $this->assertEquals($dataUpdateForUser['name'], $userResponse->name);
    }

    public function testUserNotAdminTryingUpdateHisRegisterWithInvalidDatas()
    {
        $user = factory(User::class)->create();

        $dataUpdateForUser = [
            'name' => 123,
            'cpf' => '111111111',
        ];

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlUser, $user->id),
            $dataUpdateForUser
        );

        $response->assertStatus(422);
    }

    public function testUserNotAdminTryingUpdateOtherUserRegisterWithInvalidDatas()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();

        $dataUpdateForUser = [
            'name' => 'New Name'
        ];

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlUser, $otherUser->id),
            $dataUpdateForUser
        );

        $response->assertForbidden();
    }


    public function testUserNotAdminTryingDeleteHisRegisterWithoutPassesId()
    {
        $user = factory(User::class)->create();

        $dataUpdateForUser = [
            'name' => 'New Name'
        ];

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlUser),
            $dataUpdateForUser
        );

        $response->assertStatus(405);
    }

    public function testUserNotAdminTryingDeleteOtherUserRegisterWithInvalidDatas()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlUser, $otherUser->id)
        );

        $response->assertForbidden();
    }
}
