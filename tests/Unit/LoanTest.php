<?php

namespace Tests\Unit;

use App\Models\Collection;
use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class LoanTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    private $urlLoan;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlLoan = "{$this->url}loans";
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

    public function testRegisterLoanSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postLoan = factory(Loan::class)->make()->toArray();
        $postCollectionCopy['collectionCopy'][0] =
            factory(CollectionCopy::class)->create(
                [
                    'isAvailable' => 1,
                    'idCollection' => factory(Collection::class)
                ]
            )->toArray();

        $postLoan = array_merge($postLoan, $postCollectionCopy);

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertStatus(405);
    }

    public function testRegisterLoanFailedWithInvalidData()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postLoan = factory(Loan::class)->make(
            [
                'loansIdentifier' => $this->faker->text(100),
                'returnDate' => $this->faker->dateTimeInInterval('now', '-5 days'),
                'expectedReturnDate' => $this->faker->dateTimeInInterval('now', '-7 days'),
                'observation' => $this->faker->randomNumber(5),
                'idOperatorUser' => factory(User::class),
                'idBorrowerUser' => factory(User::class),
            ]
        )->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertStatus(422);
    }

    public function testUpdateLoanSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $loan = factory(Loan::class)->create();

        $dataUpdateForLoan = [
            'returnDate' => Carbon::now()->addDays(4)->toDateTimeString(),
            'observation' => $this->faker->text(),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlLoan, $loan->idLoan),
            $dataUpdateForLoan
        );

        $response->assertOk();

        $getLoan = $response->getData()->loan;

        $this->assertEquals($getLoan->returnDate, $dataUpdateForLoan['returnDate']);
        $this->assertEquals($getLoan->observation, $dataUpdateForLoan['observation']);
    }

    public function testViewLoanDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $loan = factory(Loan::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson(
            $this->urlWithParameter($this->urlLoan, $loan->idLoan)
        );

        $response->assertOk();

        $this->createAndAuthenticateTheUserNotAdmin(
            [
                'idProfile' => $this->userProfile
            ]
        );


        $loan = factory(Loan::class)->create(
            [
                'idBorrowerUser' => $this->userNotAdmin->id
            ]
        );


        $response = $this->getJson(
            $this->urlWithParameter($this->urlLoan, $loan->idLoan)
        );

        $response->assertOk();
    }

    public function testDeleteLoanSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $loan = factory(Loan::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlLoan, $loan->idLoan)
        );

        $response->assertOk();

        $loanWasDeleted = isset(Loan::withTrashed()->find($loan->idLoan)->deleted_at);

        $this->assertTrue($loanWasDeleted);
    }

    public function testUserNotAdminTruingDeleteLoanUnsuccessfully()
    {
        $user = factory(User::class)->create();

        $loan = factory(Loan::class)->create();


        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlLoan, $loan->idLoan)
        );

        $response->assertForbidden();
    }
}
