<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    public $url = 'api/v1/';

    protected $userAdmin;

    protected $userNotAdmin;

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

    protected function createAndAuthenticateTheUserNotAdmin()
    {
        $this->userNotAdmin = factory(User::class)->create(
            [
                'isAdmin' => 0
            ]
        );

        Passport::actingAs($this->userNotAdmin);

        $this->assertAuthenticatedAs($this->userNotAdmin, 'api');
    }
}
