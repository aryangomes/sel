<?php

namespace Tests\Feature\Loan;

use App\Models\Loan\Loan;
use App\Models\Loan\StatusLoan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;
use Tests\TestCase;

class ReturnLoanTest extends BaseLoanTest
{
    use RefreshDatabase, WithFaker;

    private $urlLoan;

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
        $this->generatePermissionsLoanProfileToUserNotAdmin(true);

        $loan = factory(Loan::class)->create(
            [
                'status' => Loan::status()[0],
                'expectedReturnDate' => Carbon::now()->addDays(7),
            ]
        );

        $this->assertTrue((bool) $loan->isPending());

        $idLoan = ['idLoan' => $loan->idLoan];

        $response = $this->patchJson(
            $this->generateUrl($loan->idLoan),
            $idLoan
        );

        $response->assertOk();

        $this->assertTrue((bool) $this->getLoanFromResponse($response)->isReturned());
    }

    public function testReturnLoanUnsuccessfullyWithReturnDateGreaterExpectedReturnDate()
    {
        $this->generatePermissionsLoanProfileToUserNotAdmin(true);

        $loan = factory(Loan::class)->create(
            [
                'status' => Loan::status()[0],
                'expectedReturnDate' => Carbon::now()->subDays(7),
            ]
        );

        $this->assertTrue((bool) $loan->isPending());

        $idLoan = ['idLoan' => $loan->idLoan];

        $response = $this->patchJson(
            $this->generateUrl($loan->idLoan),
            $idLoan
        );

        $response->assertStatus(422);

        $this->assertTrue((bool) $loan->isPending());
    }

    private function generateUrl($idLoan)
    {
        return "{$this->urlLoan}{$idLoan}/return";
    }
}
