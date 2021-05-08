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

    public function testViewAllAcquistionTypeDataSuccessfully()
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


    public function testRegisterAcquistionTypeSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postAcquistionType = factory(AcquisitionType::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlAcquisitionType, $postAcquistionType);

        $response->assertCreated();
    }
}
