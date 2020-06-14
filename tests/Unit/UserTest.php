<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function testRegisterUserNotAdmin()
    {
        $userPost = factory(User::class)->make()->toArray();
        
        $userPost['password'] = env('DEFAULT_PASSWORD_NOT_ADMIN');
        $userPost['password_confirmation'] =$userPost['password'];
        
        $response = $this->postJson($this->urlUser, $userPost);

        $response->assertCreated();
    }
}
