<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class UserTest extends BaseTest
{
    use RefreshDatabase;

    private $urlUser;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlUser = "{$this->url}users";
        parent::setUp();
        $this->generateProfile();

        $this->generateProfilePermissions('users');
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

    public function testUserNotAdminTryingRegisterUserNotAdmin()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $userPost = factory(User::class)->make()->toArray();

        $userPost['password'] = env('DEFAULT_PASSWORD_NOT_ADMIN');
        $userPost['password_confirmation'] = $userPost['password'];

        $response = $this->postJson($this->urlUser, $userPost);

        $response->assertForbidden();
    }

    public function testUpdateUserNotAdminSuccessfully()
    {
        $this->createAndAuthenticateTheUserNotAdmin(
            [

                'idProfile' => $this->userProfile,
            ]
        );

        $dataUpdateForUser = [
            'name' => 'New Name'
        ];

        $response = $this->putJson(
            $this->urlWithParameter($this->urlUser, $this->userNotAdmin->id),
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


    public function testUserNotAdminTryingUpdateHisRegisterWithoutPassesId()
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

    public function testUserNotAdminViewOwnDataSuccessfully()
    {
        $user = factory(User::class)->create(
            [

                'idProfile' => $this->userProfile,
            ]
        );

        $credentials = [
            'cpf' => $user->cpf,
            'password' => env('DEFAULT_PASSWORD_NOT_ADMIN')
        ];

        $response = $this->postJson($this->url . 'login/', $credentials);

        $accessToken = $response->getData()->success->token;

        $response->assertOk();


        $response = $this->withHeader(
            'Authorization',
            $user->getAuthorizationBearerHeader($accessToken)
        )->getJson(
            $this->urlWithParameter($this->urlUser, $user->id)
        );

        $response->assertOk();
    }

    public function testUserNotAdminTryingViewDataOfAnotherUserNotAdmin()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();

        $credentials = [
            'cpf' => $user->cpf,
            'password' => env('DEFAULT_PASSWORD_NOT_ADMIN')
        ];

        $response = $this->postJson($this->url . 'login/', $credentials);

        $accessToken = $response->getData()->success->token;

        $response->assertOk();


        $response = $this->withHeader(
            'Authorization',
            $user->getAuthorizationBearerHeader($accessToken)
        )->getJson(
            $this->urlWithParameter($this->urlUser, $otherUser->id)
        );

        $response->assertForbidden();
    }

    public function testUserNotAdminDeleteSuccessfully()
    {
        $user = factory(User::class)->create(
            [

                'idProfile' => $this->userProfile,
            ]
        );

        $credentials = [
            'cpf' => $user->cpf,
            'password' => env('DEFAULT_PASSWORD_NOT_ADMIN')
        ];

        $response = $this->postJson($this->url . 'login', $credentials);

        $accessToken = $response->getData()->success->token;

        $response->assertOk();


        $response = $this->withHeader(
            'Authorization',
            $user->getAuthorizationBearerHeader($accessToken)
        )->deleteJson(
            $this->urlWithParameter($this->urlUser, $user->id)
        );

        $response->assertOk();

        $userWasDeleted = isset(User::withTrashed()->find($user->id)->deleted_at);
        $this->assertTrue($userWasDeleted);
    }

    public function testUserNotAdminTryingDeleteOtherUser()
    {


        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();

        $credentials = [
            'cpf' => $user->cpf,
            'password' => config('user.default_password_not_admin')
        ];

        $response = $this->postJson($this->url . 'login', $credentials);

        $accessToken = $response->getData()->success->token;

        $response->assertOk();

        $response = $this->withHeader(
            'Authorization',
            $user->getAuthorizationBearerHeader($accessToken)
        )->deleteJson(
            $this->urlWithParameter($this->urlUser, $otherUser->id)
        );

        $response->assertForbidden();
    }

    public function testUserAdminDeleteSuccessfully()
    {
        $user = factory(User::class)->create(
            ['isAdmin' => 1]
        );

        $credentials = [
            'email' => $user->email,
            'password' => config('user.default_password_admin')
        ];

        $response = $this->postJson($this->url . 'login/admin', $credentials);
        $accessToken = $response->getData()->success->token;

        $response->assertOk();


        $response = $this->withHeader(
            'Authorization',
            $user->getAuthorizationBearerHeader($accessToken)
        )->deleteJson(
            $this->urlWithParameter($this->urlUser, $user->id)
        );

        $response->assertOk();

        $userWasDeleted = isset(User::withTrashed()->find($user->id)->deleted_at);
        $this->assertTrue($userWasDeleted);
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
