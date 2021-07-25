<?php

namespace Tests\Unit;

use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanContainsCollectionCopy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class LoanContainsCollectionCopyTest extends BaseTest
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
        $this->generateProfile();

        $this->generateProfilePermissions('loan_contains_collection_copies');
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

        $this->createAndAuthenticateTheAdminUser();


        $postLoanContainsCollectionCopy = factory(LoanContainsCollectionCopy::class)
            ->make([])->toArray();

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
                'idLoan' => factory(Loan::class),
                'idCollectionCopy' => 'id1',

            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlLoanContainsCollectionCopy, $postLoanContainsCollectionCopy);

        $response->assertStatus(422);
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

        $this->createAndAuthenticateTheUserNotAdmin(
            [

                'idProfile' => $this->userProfile,
            ]
        );

        $loan = factory(LoanContainsCollectionCopy::class)->create();

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlLoanContainsCollectionCopy, $loan->idLoanContainsCollectionCopy)
        );

        $response->assertForbidden();
    }
}
