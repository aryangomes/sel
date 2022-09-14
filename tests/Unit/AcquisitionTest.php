<?php

namespace Tests\Unit;

use App\Models\Acquisition;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileHasPermission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class AcquisitionTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    protected $urlAcquisition;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlAcquisition = "{$this->url}acquisitions";
        parent::setUp();
        $this->generateProfile();

        $this->generateProfilePermissions('acquisitions');
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testViewAllAcquisitionDataSuccessfully()
    {
        $acquisition = factory(Acquisition::class)->create();

        $this->createAndAuthenticateTheAdminUser();

        $response = $this->getJson($this->urlAcquisition);

        $response->assertOk();

        $this->createAndAuthenticateTheUserNotAdmin(
            [

                'idProfile' => $this->userProfile,
            ]
        );


        $response = $this->getJson($this->urlAcquisition);

        $response->assertOk();
    }


    public function testRegisterAcquisitionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postAcquisition = factory(Acquisition::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlAcquisition, $postAcquisition);

        $response->assertCreated();
    }

    public function testRegisterAcquisitionUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postAcquisition = factory(Acquisition::class)->make()->toArray();


        unset($postAcquisition['price']);
        unset($postAcquisition['quantity']);

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlAcquisition, $postAcquisition);

        $response->assertStatus(422);
    }

    public function testUpdateAcquisitionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisition = factory(Acquisition::class)->create();

        $dataUpdateForAcquisition = [
            'price' => $this->faker->randomFloat(6, 0.01, 1000000),
            'quantity' => $this->faker->numberBetween(1, 1000000),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlAcquisition, $acquisition->idAcquisition),
            $dataUpdateForAcquisition
        );

        $response->assertOk();
        $getAcquisition = $response->getData()->acquisition;

        $this->assertEquals($getAcquisition->price, $dataUpdateForAcquisition['price']);
        $this->assertEquals($getAcquisition->quantity, $dataUpdateForAcquisition['quantity']);
    }

    public function testUpdateAcquisitionUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisition = factory(Acquisition::class)->create();

        $dataUpdateForAcquisition = [];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlAcquisition, $acquisition->idAcquisition),
            $dataUpdateForAcquisition
        );

        $response->assertStatus(422);
    }


    public function testDeleteAcquisitionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisition = factory(Acquisition::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlAcquisition, $acquisition->idAcquisition)
        );

        $response->assertOk();

        $acquisitionWasDeleted = isset(Acquisition::withTrashed()->find($acquisition->idAcquisition)->deleted_at);

        $this->assertTrue($acquisitionWasDeleted);
    }

    public function testDeleteAcquisitionUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $acquisition = factory(Acquisition::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlAcquisition)
        );

        $response->assertStatus(405);
    }
}
