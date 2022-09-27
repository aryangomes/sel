<?php

namespace Tests\Feature\Loan;

use App\Models\Collection;
use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanContainsCollectionCopy;
use App\Models\Loan\StatusLoan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;
use Tests\TestCase;

class ReturnLoanTest extends BaseLoanTest
{
    use RefreshDatabase, WithFaker;

    private $urlLoan;
    private $loan;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlLoan = "{$this->url}loan/";
        parent::setUp();

        $this->generateProfile();

        $this->generateProfilePermissions('loans');
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testReturnLoanSuccessfully()
    {

        $this->generateLoan();

        $this->assertTrue((bool) $this->loan->isInLoan());

        $response = $this->patchJson(
            route(
                'loans.return',
                [
                    'loan' => $this->loan->idLoan
                ]
            )

        );



        $response->assertOk();

        $this->assertTrue((bool) $this->getLoanFromResponse($response)->isReturned());

        $this->loan = $this->getLoanFromResponse($response);
        $loanCollectionCopies = $this->loan->containCopies;

        foreach ($loanCollectionCopies as $collectionCopy) {
            $this->assertTrue((bool) CollectionCopy::find($collectionCopy->idCollectionCopy)
                ->isAvailable);
        }
    }

    public function testReturnLoanUnsuccessfullyWithReturnDateGreaterExpectedReturnDate()
    {
        $this->generatePermissionsLoanProfileToUserNotAdmin(true);

        $loan = factory(Loan::class)->create(
            [
                'expectedReturnDate' => Carbon::now()->subDays(7),
            ]
        );

        $this->assertTrue((bool) $loan->isInLoan());

        $response = $this->patchJson(
            route(
                'loans.return',
                [
                    'loan' => $loan->idLoan
                ]
            )

        );


        $response->assertStatus(422);

        $this->assertTrue((bool) $loan->isInLoan());
    }

    private function generateUrl($idLoan)
    {
        return "{$this->urlLoan}{$idLoan}/return";
    }

    private function generateLoan($copiesQuantity = 10)
    {
        $this->generatePermissionsLoanProfileToUserNotAdmin(true);

        $borrowerUser =
            factory(User::class)->create();

        $postLoan = factory(Loan::class)->make(
            [
                'idBorrowerUser' => $borrowerUser
            ]
        )->toArray();

        $postCollectionCopyArray = [];

        $collections = factory(Collection::class, $copiesQuantity)->create()
            ->each(function ($collection) {
                $collection->copies()->createMany(factory(CollectionCopy::class, 3)->make()->toArray());
            });

        foreach ($collections as $key => $collection) {

            $postCollectionCopyArray['collectionCopy'][$key] = $collection->copies->random();
        }

        $postLoan = array_merge($postLoan, $postCollectionCopyArray);

        $response = $this->postJson(route('loans.register'), $postLoan);

        $response->assertCreated();

        $this->loan = Loan::find($response->getData()->loan->idLoan);
    }
}
