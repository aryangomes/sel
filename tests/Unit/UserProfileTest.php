<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $urlUserProfile;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlUserProfile = "{$this->url}userProfiles";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterUserProfileSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postUserProfile = factory(UserProfile::class)->make()->toArray();

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlUserProfile, $postUserProfile);

        $response->assertCreated();
    }

    public function testRegisterUserProfileFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postUserProfile = factory(UserProfile::class)->make(
            [
                'profile' => $this->faker->randomDigit,

            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlUserProfile, $postUserProfile);

        $response->assertStatus(422);
    }

    public function testUpdateUserProfileSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $userProfile = factory(UserProfile::class)->create();

        $dataUpdateForUserProfile = [
            'profile' => $this->faker->numerify('Profile ###'),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlUserProfile, $userProfile->idProfile),
            $dataUpdateForUserProfile
        );

        $response->assertOk();

        $getUserProfile = $response->getData()->userProfile;

        $this->assertEquals(
            $getUserProfile->profile,
            $dataUpdateForUserProfile['profile']
        );
    }

    public function testViewUserProfileDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $userProfile = factory(UserProfile::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlUserProfile, $userProfile->idProfile)
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
            $this->urlWithParameter($this->urlUserProfile, $userProfile->idProfile)
        );

        $response->assertOk();
    }

    public function testDeleteUserProfileSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $userProfile = factory(UserProfile::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlUserProfile, $userProfile->idProfile)
        );

        $response->assertOk();

        $userProfileWasDeleted = isset(UserProfile::withTrashed()->find($userProfile->idProfile)->deleted_at);

        $this->assertTrue($userProfileWasDeleted);
    }

    public function testUserNotAdminTruingDeleteUserProfileUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $userProfile = factory(UserProfile::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlUserProfile, $userProfile->idProfile)
        );

        $response->assertForbidden();
    }
}
