<?php

namespace Tests\Unit;

use App\Models\Lender;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class LenderTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    private $urlLender;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlLender = "{$this->url}lenders";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterLenderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postLender = factory(Lender::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlLender, $postLender);

        $response->assertCreated();
    }

    public function testRegisterLenderFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postLender = factory(Lender::class)->make(
            [
                'name' => null,
                'streetAddress' => null,
                'email' => 'email',
                'numberAddress' => 123,

                'neighborhoodAddress' => null,
                'phoneNumber' => null,
                'cellNumber' =>  null,
                'complementAddress' =>  null,
                'site' =>  'null',
            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlLender, $postLender);

        $response->assertStatus(422);
    }

    public function testUpdateLenderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $lender = factory(Lender::class)->create();

        $dataUpdateForLender = [
            'name' => $this->faker->name,
            'streetAddress' => $this->faker->streetAddress,
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlLender, $lender->idLender),
            $dataUpdateForLender
        );

        $response->assertOk();

        $getLender = $response->getData()->lender;

        $this->assertEquals($getLender->name, $dataUpdateForLender['name']);
    }

    public function testViewLenderDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $lender = factory(Lender::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlLender, $lender->idLender)
        );

        $response->assertOk();


        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlLender, $lender->idLender)
        );

        $response->assertOk();
    }

    public function testDeleteLenderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $lender = factory(Lender::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlLender, $lender->idLender)
        );

        $response->assertOk();

        $lenderWasDeleted = isset(Lender::withTrashed()->find($lender->idLender)->deleted_at);

        $this->assertTrue($lenderWasDeleted);
    }

    public function testUserNotAdminTruingDeleteLenderUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $lender = factory(Lender::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlLender, $lender->idLender)
        );

        $response->assertForbidden();
    }
}
