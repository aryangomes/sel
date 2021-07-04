<?php

namespace Tests\Feature;

use Tests\BaseTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends BaseTest
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
