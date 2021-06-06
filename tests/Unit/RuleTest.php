<?php

namespace Tests\Unit;

use App\Models\Rule;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RuleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $urlRule;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlRule = "{$this->url}rules";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterRuleSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postRule = factory(Rule::class)->make()->toArray();

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlRule, $postRule);

        $response->assertCreated();
    }

    public function testRegisterRuleFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postRule = factory(Rule::class)->make(
            [
                'rule' => $this->faker->randomDigit,
                'can' => $this->faker->randomDigit,
                'idUserProfile' => factory(UserProfile::class),

            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlRule, $postRule);

        $response->assertStatus(422);
    }

    public function testUpdateRuleSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $rule = factory(Rule::class)->create();

        $dataUpdateForRule = [
            'rule' => $this->faker->lexify('can.?????'),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlRule, $rule->idRule),
            $dataUpdateForRule
        );

        $response->assertOk();

        $getRule = $response->getData()->rule;

        $this->assertEquals(
            $getRule->rule,
            $dataUpdateForRule['rule']
        );
    }

    public function testUpdateRuleFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $rule = factory(Rule::class)->create();

        $dataUpdateForRule = [
            'rule' => $this->faker->randomDigit,
            'can' => $this->faker->lexify('?????'),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlRule, $rule->idRule),
            $dataUpdateForRule
        );

        $response->assertStatus(422);

        $response = $this->getJson(
            $this->urlWithParameter($this->urlRule, $rule->idRule)
        );

        $this->assertNotEquals(
            $response->getData()->data->rule,
            $dataUpdateForRule['rule']
        );
    }

    public function testViewRuleDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $rule = factory(Rule::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlRule, $rule->idRule)
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
            $this->urlWithParameter($this->urlRule, $rule->idRule)
        );

        $response->assertOk();
    }

    public function testDeleteRuleSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $rule = factory(Rule::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlRule, $rule->idRule)
        );

        $response->assertOk();

        $ruleWasDeleted = isset(Rule::withTrashed()->find($rule->idRule)->deleted_at);

        $this->assertTrue($ruleWasDeleted);
    }

    public function testUserNotAdminTruingDeleteRuleUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $rule = factory(Rule::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlRule, $rule->idRule)
        );

        $response->assertForbidden();
    }
}
