<?php

namespace Tests\Unit\Login;

use App\Http\Models\Utils\Regex;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoginTest extends TestCase
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
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $credentials = [
            'email' => $userAdmin->email,
            'password' => env('DEFAULT_PASSWORD_ADMIN')
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $response->assertOk();
    }

    public function testUserTryingLoginAdministratorIsNotAdministrator()
    {
        $userIsNotAdmin = factory(User::class)->create();

        $credentials = [
            'email' => $userIsNotAdmin->email,
            'password' => env('DEFAULT_PASSWORD_ADMIN')
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $response->assertStatus(400);
    }

    public function testUserTryingLoginAdministratorWithInvalidCredentials()
    {
        $credentials = [
            'email' => 'admin',
            'password' => env('DEFAULT_PASSWORD_ADMIN')
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
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $credentials = [
            'email' => $userAdmin->email,
            'password' => env('DEFAULT_PASSWORD_ADMIN')
        ];

        $response = $this->postJson($this->urlLogin . 'admin', $credentials);

        $accessToken = $response->getData()->success->token;

        $response->assertOk();

        $response = $this->withHeader(
            'Authorization',
            $userAdmin->getAuthorizationBearerHeader($accessToken)
        )
            ->getJson($this->url . 'logout');

        $response->assertStatus(204);
    }


    public function testLoginUserNotAdministrator()
    {
        $userNotAdmin = factory(User::class)->create();

        $credentials = [
            'cpf' => $userNotAdmin->cpf,
            'password' => env('DEFAULT_PASSWORD_ADMIN')
        ];

        $response = $this->postJson($this->urlLogin . '/', $credentials);

        $response->assertOk();
    }

    public function testLoginUserNotAdministratorTryingLoginAsUserAdministrator()
    {
        $userNotAdmin = factory(User::class)->create();

        $credentials = [
            'email' => $userNotAdmin->email,
            'password' => env('DEFAULT_PASSWORD_ADMIN')
        ];

        $response = $this->postJson($this->urlLogin . '/', $credentials);

        $response->assertStatus(422);
    }

    public function testUserNotAdministratorTryingLoginAdministratorWithInvalidCredentials()
    {
        $userNotAdmin = factory(User::class)->create();

        $credentials = [
            'name' => $userNotAdmin->name,
            'password' => env('DEFAULT_PASSWORD_ADMIN')
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
        $userNotAdmin = factory(User::class)->create();

        $credentials = [
            'cpf' => $userNotAdmin->cpf,
            'password' => env('DEFAULT_PASSWORD_ADMIN')
        ];

        $response = $this->postJson($this->urlLogin, $credentials);


        $accessToken = $response->getData()->success->token;

        $response->assertOk();

        $response = $this->withHeader(
            'Authorization',
            $userNotAdmin->getAuthorizationBearerHeader($accessToken)
        )
            ->getJson($this->url . 'logout');

        $response->assertStatus(204);
    }
}
