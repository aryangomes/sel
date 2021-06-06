<?php

namespace Tests\Unit;

use App\Models\JuridicPerson;
use App\Models\NaturalPerson;
use App\Models\Provider;
use App\Models\User;
use App\Models\Utils\Regex;
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
        $this->urlProvider = "{$this->url}providers";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterProviderFailed()
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


        $response->assertStatus(422);
    }

    public function testRegisterNaturalPersonProviderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postProvider = factory(Provider::class)->make()->toArray();

        $postProvider['cpf'] = $this->faker->regexify(Regex::CPF);

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlProvider, $postProvider);

        $naturalPersonCreated = $response->getData()->provider;

        $response->assertCreated();

        $this->assertEquals($naturalPersonCreated->naturalPerson->cpf, $postProvider['cpf']);
    }

    public function testRegisterJuridicPersonProviderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postProvider = factory(Provider::class)->make()->toArray();
        $postProvider['cnpj'] = $this->faker->regexify(Regex::CNPJ);


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlProvider, $postProvider);


        $response->assertCreated();

        $naturalPersonCreated = $response->getData()->provider;

        $response->assertCreated();

        $this->assertEquals($naturalPersonCreated->juridicPerson->cnpj, $postProvider['cnpj']);
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
                'complementAddress' =>  null,
                'cpf' =>  '12345678',
                'cnpj' =>  '12345678',
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

    public function testDeleteNaturalPersonProviderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $naturalPersonProvider = factory(NaturalPerson::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlProvider, $naturalPersonProvider->idProvider)
        );

        $response->assertOk();

        $providerWasDeleted = isset(Provider::withTrashed()->find($naturalPersonProvider->idProvider)->deleted_at);

        $naturalPersonProviderWasDeleted = isset(NaturalPerson::withTrashed()->find($naturalPersonProvider->idNaturalPerson)->deleted_at);

        $this->assertTrue($providerWasDeleted);
        $this->assertTrue($naturalPersonProviderWasDeleted);
    }

    public function testDeleteJuridicPersonProviderSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $juridicPersonProvider = factory(JuridicPerson::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlProvider, $juridicPersonProvider->idProvider)
        );

        $response->assertOk();

        $providerWasDeleted = isset(Provider::withTrashed()->find($juridicPersonProvider->idProvider)->deleted_at);

        $juridicPersonProviderWasDeleted = isset(JuridicPerson::withTrashed()->find($juridicPersonProvider->idJuridicPerson)->deleted_at);

        $this->assertTrue($providerWasDeleted);
        $this->assertTrue($juridicPersonProviderWasDeleted);
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
