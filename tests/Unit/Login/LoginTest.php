<?php

namespace Tests\Unit\Login;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class LoginTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    public $urlLogin;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlLogin = $this->url . 'login/';
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }


    public function testLoginUserAdministrator()
    {
        $userAdmin =
            factory(User::class)->states('admin')->create();;

        $credentials = [
            'email' => $userAdmin->email,
            'password' => config('user.default_password_admin')
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $response->assertOk();
    }

    public function testUserTryingLoginAdministratorIsNotAdministrator()
    {
        $userIsNotAdmin = factory(User::class)->states('notAdmin')->create();

        $credentials = [
            'email' => $userIsNotAdmin->email,
            'password' => config('user.default_password_not_admin')
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $response->assertStatus(400);
    }

    public function testUserTryingLoginAdministratorWithInvalidCredentials()
    {
        $credentials = [
            'email' => 'admin',
            'password' => config('user.default_password_admin')
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $response->assertStatus(422);
    }

    public function testUserAdministratorTryingLoginAdministratorWithWrongPassword()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $credentials = [
            'email' => $userAdmin->email,
            'password' => 'vqwv561qw156'
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $response->assertStatus(422);
    }

    public function testLogoutUserAdministrator()
    {
        $userAdmin = factory(User::class)->states('admin')->create();

        $credentials = [
            'email' => $userAdmin->email,
            'password' => config('user.default_password_admin')
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $accessToken = $response->getData()->success->token;

        $response->assertOk();

        $response = $this->withHeader(
            'Authorization',
            $userAdmin->getAuthorizationBearerHeader($accessToken)
        )
            ->postJson($this->url . 'logout');

        $response->assertStatus(204);
    }


    public function testLoginUserNotAdministrator()
    {
        $userNotAdmin = factory(User::class)->states('notAdmin')->create();

        $credentials = [
            'cpf' => $userNotAdmin->cpf,
            'password' => config('user.default_password_not_admin')
        ];

        $response = $this->postJson($this->urlLogin . '/', $credentials);

        $response->assertOk();
    }

    public function testLoginUserNotAdministratorTryingLoginAsUserAdministrator()
    {
        $userNotAdmin = factory(User::class)->states('notAdmin')->create();

        $credentials = [
            'email' => $userNotAdmin->email,
            'password' => config('user.default_password_not_admin')
        ];

        $response = $this->postJson($this->urlLogin . '/', $credentials);

        $response->assertStatus(422);
    }

    public function testUserNotAdministratorTryingLoginAdministratorWithInvalidCredentials()
    {
        $userNotAdmin = factory(User::class)->states('notAdmin')->create();

        $credentials = [
            'name' => $userNotAdmin->name,
            'password' => config('user.default_password_not_admin')
        ];

        $response = $this->postJson($this->urlLogin . '/', $credentials);

        $response->assertStatus(422);
    }

    public function testUserNotAdministratorTryingLoginWithWrongPassword()
    {
        $userIsNotAdmin = factory(User::class)->create();

        $credentials = [
            'cpf' => $userIsNotAdmin->cpf,
            'password' => 'asdwq1d5qw61d'
        ];

        $response = $this->postJson($this->urlLogin . '/', $credentials);

        $response->assertStatus(422);
    }

    public function testLogoutUserNotAdministrator()
    {
        $userNotAdmin = factory(User::class)->states('notAdmin')->create();

        $credentials = [
            'cpf' => $userNotAdmin->cpf,
            'password' => config('user.default_password_not_admin')
        ];

        $response = $this->postJson($this->urlLogin, $credentials);


        $accessToken = $response->getData()->success->token;

        $response->assertOk();

        $response = $this->withHeader(
            'Authorization',
            $userNotAdmin->getAuthorizationBearerHeader($accessToken)
        )
            ->postJson($this->url . 'logout');

        $response->assertStatus(204);
    }
}
