<?php

namespace Tests\Unit;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProviderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $urlProvider;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlProvider = $this->url . 'provider';
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterProviderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postProvider = factory(Provider::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlProvider, $postProvider);


        $response->assertCreated();
    }

    public function testRegisterProviderFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postProvider = factory(Provider::class)->make(
            [
                'name' => null,
                'streetAddress' => null,
                'email' => 'email',
                'numberAddress' => 123,
                'neighborhoodAddress' => null,
                'phoneNumber' => null,
                'cellNumber' =>  null,
                'complementAddress' =>  null
            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlProvider, $postProvider);

        $response->assertStatus(422);
    }

    public function testUpdateProviderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $provider = factory(Provider::class)->create();

        $dataUpdateForProvider = [
            'name' => $this->faker->name,
            'streetAddress' => $this->faker->streetAddress,
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlProvider, $provider->idProvider),
            $dataUpdateForProvider
        );

        $response->assertOk();

        $getProvider = $response->getData()->provider;

        $this->assertEquals($getProvider->name, $dataUpdateForProvider['name']);
    }

    public function testViewProviderDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $provider = factory(Provider::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlProvider, $provider->idProvider)
        );

        $response->assertOk();


        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlProvider, $provider->idProvider)
        );

        $response->assertForbidden();
    }

    public function testDeleteProviderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $provider = factory(Provider::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlProvider, $provider->idProvider)
        );

        $response->assertOk();

        $providerWasDeleted = isset(Provider::withTrashed()->find($provider->idProvider)->deleted_at);

        $this->assertTrue($providerWasDeleted);
    }

    public function testUserNotAdminTruingDeleteProviderUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $provider = factory(Provider::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlProvider, $provider->idProvider)
        );

        $response->assertForbidden();
    }
}
