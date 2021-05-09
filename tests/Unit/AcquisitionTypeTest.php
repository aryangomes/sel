<?php

namespace Tests\Unit;

use App\Models\AcquisitionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AcquisitionTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $urlAcquisitionType;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlAcquisitionType = "{$this->url}acquisitionType";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testViewAllAcquisitionTypeDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisitionType = factory(AcquisitionType::class)->create();


        Passport::actingAs($userAdmin);

        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson($this->urlAcquisitionType);

        $response->assertOk();


        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson($this->urlAcquisitionType);

        $response->assertOk();
    }


    public function testRegisterAcquisitionTypeSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postAcquisitionType = factory(AcquisitionType::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlAcquisitionType, $postAcquisitionType);

        $response->assertCreated();
    }

    public function testRegisterAcquisitionTypeUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postAcquisitionType = factory(AcquisitionType::class)->make()->toArray();


        unset($postAcquisitionType['type']);

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlAcquisitionType, $postAcquisitionType);

        $response->assertStatus(422);
    }

    public function testUpdateAcquisitionTypeSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisitionType = factory(AcquisitionType::class)->create();

        $dataUpdateForAcquisitionType = [
            'type' => $this->faker->word,
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlAcquisitionType, $acquisitionType->idAcquisitionType),
            $dataUpdateForAcquisitionType
        );

        $response->assertOk();

        $getAcquisitionType = $response->getData()->acquisitionType;

        $this->assertEquals($getAcquisitionType->type, $dataUpdateForAcquisitionType['type']);
    }

    public function testUpdateAcquisitionTypeUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisitionType = factory(AcquisitionType::class)->create();

        $dataUpdateForAcquisitionType = [];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlAcquisitionType, $acquisitionType->idAcquisitionType),
            $dataUpdateForAcquisitionType
        );

        $response->assertStatus(422);
    }


    public function testDeleteAcquisitionTypeSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisitionType = factory(AcquisitionType::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlAcquisitionType, $acquisitionType->idAcquisitionType)
        );

        $response->assertOk();

        $acquisitionTypeWasDeleted = isset(AcquisitionType::withTrashed()->find($acquisitionType->idAcquisitionType)->deleted_at);

        $this->assertTrue($acquisitionTypeWasDeleted);
    }

    public function testDeleteAcquisitionTypeUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisitionType = factory(AcquisitionType::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlAcquisitionType)
        );

        $response->assertStatus(405);
    }
}
