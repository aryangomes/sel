<?php

namespace Tests\Unit;

use App\Models\CollectionCopy;
use App\Models\Loan;
use App\Models\LoanContainsCollectionCopy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoanContainsCollectionCopyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $urlLoanContainsCollectionCopy;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlLoanContainsCollectionCopy = "{$this->url}loanContainsCollectionCopies";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testRegisterLoanContainsCollectionCopySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postLoanContainsCollectionCopy = factory(LoanContainsCollectionCopy::class)->make()->toArray();

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlLoanContainsCollectionCopy, $postLoanContainsCollectionCopy);

        $response->assertCreated();
    }

    public function testRegisterLoanContainsCollectionCopyFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postLoanContainsCollectionCopy = factory(LoanContainsCollectionCopy::class)->make(
            [
                'quantity' => $this->faker->numberBetween(-11, 0),
                'idLoan' => factory(Loan::class),
                'idCollectionCopy' => factory(CollectionCopy::class),

            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlLoanContainsCollectionCopy, $postLoanContainsCollectionCopy);

        $response->assertStatus(422);
    }

    public function testUpdateLoanContainsCollectionCopySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $loan = factory(LoanContainsCollectionCopy::class)->create();

        $dataUpdateForLoanContainsCollectionCopy = [
            'quantity' => $this->faker->numberBetween(1, 1000000),

        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlLoanContainsCollectionCopy, $loan->idLoanContainsCollectionCopy),
            $dataUpdateForLoanContainsCollectionCopy
        );

        $response->assertOk();

        $getLoanContainsCollectionCopy = $response->getData()->loanContainsCollectionCopy;

        $this->assertEquals(
            $getLoanContainsCollectionCopy->quantity,
            $dataUpdateForLoanContainsCollectionCopy['quantity']
        );
    }

    public function testViewLoanContainsCollectionCopyDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $loanContainsCollectionCopy = factory(LoanContainsCollectionCopy::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlLoanContainsCollectionCopy, $loanContainsCollectionCopy->idLoanContainsCollectionCopy)
        );

        $response->assertOk();
    }

    public function testDeleteLoanContainsCollectionCopySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $loan = factory(LoanContainsCollectionCopy::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlLoanContainsCollectionCopy, $loan->idLoanContainsCollectionCopy)
        );

        $response->assertOk();

        $loanWasDeleted = isset(LoanContainsCollectionCopy::withTrashed()->find($loan->idLoanContainsCollectionCopy)->deleted_at);

        $this->assertTrue($loanWasDeleted);
    }

    public function testUserNotAdminTruingDeleteLoanContainsCollectionCopyUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $loan = factory(LoanContainsCollectionCopy::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlLoanContainsCollectionCopy, $loan->idLoanContainsCollectionCopy)
        );

        $response->assertForbidden();
    }
}
