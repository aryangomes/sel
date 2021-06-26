<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class ProfileTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    private $urlProfile;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlProfile = "{$this->url}profiles";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterProfileSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postProfile = factory(Profile::class)->make()->toArray();

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlProfile, $postProfile);

        $response->assertCreated();
    }

    public function testRegisterProfileFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postProfile = factory(Profile::class)->make(
            [
                'profile' => $this->faker->randomDigit,

            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlProfile, $postProfile);

        $response->assertStatus(422);
    }

    public function testUpdateProfileSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $profile = factory(Profile::class)->create();

        $dataUpdateForProfile = [
            'profile' => $this->faker->numerify('Profile ###'),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlProfile, $profile->idProfile),
            $dataUpdateForProfile
        );

        $response->assertOk();

        $getProfile = $response->getData()->profile;

        $this->assertEquals(
            $getProfile->profile,
            $dataUpdateForProfile['profile']
        );
    }

    public function testViewProfileDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $profile = factory(Profile::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlProfile, $profile->idProfile)
        );

        $response->assertOk();


        $user = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlProfile, $profile->idProfile)
        );

        $response->assertOk();
    }

    public function testDeleteProfileSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $profile = factory(Profile::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlProfile, $profile->idProfile)
        );

        $response->assertOk();

        $profileWasDeleted = isset(Profile::withTrashed()->find($profile->idProfile)->deleted_at);

        $this->assertTrue($profileWasDeleted);
    }

    public function testUserNotAdminTruingDeleteProfileUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $profile = factory(Profile::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlProfile, $profile->idProfile)
        );

        $response->assertForbidden();
    }
}
