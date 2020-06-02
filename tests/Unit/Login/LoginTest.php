<?php

namespace Tests\Unit\Login;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

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
                'isAdmin'=>1
            ]
        );

        $credentials = [
            'email'=>$userAdmin->email,
            'password'=>'12345678'
        ];

        $response = $this->postJson(  $this->urlLogin . 'admin',$credentials);

        $response->dump();
        $response->assertOk();
    }
}
