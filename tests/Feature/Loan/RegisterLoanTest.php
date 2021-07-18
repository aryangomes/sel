<?php

namespace Tests\Feature\Loan;

use App\Models\Collection;
use App\Models\CollectionCopy;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;

class RegisterLoanTest extends BaseTest
{

    use RefreshDatabase, WithFaker;

    private $urlLoan;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlLoan = "{$this->url}loan/register";
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
        $this->createAndAuthenticateTheAdminUser();

        $borrowerUser =
            factory(User::class)->create();

        $postLoan = factory(Loan::class)->make(
            [
                'idBorrowerUser' => $borrowerUser
            ]
        )->toArray();
        $postCollectionCopy['collectionCopy'][0] =
            factory(CollectionCopy::class)->create(
                [
                    'isAvailable' => 1,
                    'idCollection' => factory(Collection::class)
                ]
            )->toArray();

        $postLoan = array_merge($postLoan, $postCollectionCopy);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $collectionCopy = CollectionCopy::find($postCollectionCopy['collectionCopy'][0]['idCollectionCopy']);

        $response->assertCreated();

        $this->assertFalse((bool) $collectionCopy->isAvailable);
    }

    public function testRegisterLoanSuccessfullyWithDataWithouArrayCollectionCopy()
    {
        $this->createAndAuthenticateTheAdminUser();

        $borrowerUser =
            factory(User::class)->create();

        $postLoan = factory(Loan::class)->make(
            [
                'idBorrowerUser' => $borrowerUser
            ]
        )->toArray();
        $postCollectionCopy['collectionCopy'] =
            factory(CollectionCopy::class)->create(
                [
                    'isAvailable' => 1,
                    'idCollection' => factory(Collection::class)
                ]
            )->toArray();

        $postLoan = array_merge($postLoan, $postCollectionCopy);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $collectionCopy = CollectionCopy::find($postCollectionCopy['collectionCopy']['idCollectionCopy']);

        $response->assertCreated();

        $this->assertFalse((bool) $collectionCopy->isAvailable);
    }

    public function testRegisterLoanUnsuccessfully()
    {
        $this->createAndAuthenticateTheAdminUser();

        $borrowerUser =
            factory(User::class)->create();

        $postLoan = factory(Loan::class)->make(
            [
                'idBorrowerUser' => $borrowerUser
            ]
        )->toArray();
        $postCollectionCopy['collectionCopy'] =
            factory(CollectionCopy::class)->create(
                [
                    'isAvailable' => 0,
                    'idCollection' => factory(Collection::class)
                ]
            )->toArray();

        $postLoan = array_merge($postLoan, $postCollectionCopy);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $collectionCopy = CollectionCopy::find($postCollectionCopy['collectionCopy']['idCollectionCopy']);

        $response->assertStatus(422);

        $this->assertFalse((bool) $collectionCopy->isAvailable);
    }

    public function testRegisterLoanUnsuccessfullyWithCollectionCopyWithoutAvaible()
    {
        $this->createAndAuthenticateTheAdminUser();


        $postLoan = factory(Loan::class)->make()->toArray();

        $postCollectionCopy['collectionCopy'][0] =
            factory(CollectionCopy::class)->create(
                ['isAvailable' => 0]
            )->toArray();

        $postLoan = array_merge($postLoan, $postCollectionCopy);


        $response = $this->postJson($this->urlLoan, $postLoan);

        $collectionCopy = CollectionCopy::find($postCollectionCopy['collectionCopy'][0]['idCollectionCopy']);

        $this->assertFalse((bool) $collectionCopy->isAvailable);


        $response->assertStatus(422);
    }

    public function testRegisterLoanUnsuccessfullyWithoutCollectionCopy()
    {
        $this->createAndAuthenticateTheAdminUser();


        $postLoan = factory(Loan::class)->make()->toArray();

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertStatus(422);
    }

    public function testRegisterLoanUnsuccessfullyWithUserBlocked()
    {
        $this->createAndAuthenticateTheAdminUser();

        $userNotAdmin = factory(User::class)->create(
            [
                'isAdmin' => 0,
                'isBlocked' => 1,
                'isActive' => 1,
            ]
        );

        $postLoan = factory(Loan::class)->make(
            [
                'idBorrowerUser' => $userNotAdmin
            ]
        )->toArray();

        $postCollectionCopy['collectionCopy'][0] =
            factory(CollectionCopy::class)->create(
                ['isAvailable' => 1]
            )->toArray();

        $postLoan = array_merge($postLoan, $postCollectionCopy);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $collectionCopy = CollectionCopy::find($postCollectionCopy['collectionCopy'][0]['idCollectionCopy']);

        $this->assertTrue((bool) $collectionCopy->isAvailable);

        $response->assertStatus(422);
    }

    public function testRegisterLoanUnsuccessfullyWithUserInactive()
    {
        $this->createAndAuthenticateTheAdminUser();

        $userNotAdmin = factory(User::class)->create(
            [
                'isAdmin' => 0,
                'isBlocked' => 1,
                'isActive' => 0,
            ]
        );

        $postLoan = factory(Loan::class)->make(
            [
                'idBorrowerUser' => $userNotAdmin
            ]
        )->toArray();

        $postCollectionCopy['collectionCopy'][0] =
            factory(CollectionCopy::class)->create(
                ['isAvailable' => 1]
            )->toArray();

        $postLoan = array_merge($postLoan, $postCollectionCopy);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $collectionCopy = CollectionCopy::find($postCollectionCopy['collectionCopy'][0]['idCollectionCopy']);

        $this->assertTrue((bool) $collectionCopy->isAvailable);

        $response->assertStatus(422);
    }

    public function testRegisterLoanSuccessfullyWithMultipleCollectionCopies()
    {
        $this->createAndAuthenticateTheAdminUser();

        $borrowerUser =
            factory(User::class)->create();

        $postLoan = factory(Loan::class)->make(
            [
                'idBorrowerUser' => $borrowerUser
            ]
        )->toArray();



        $postCollectionCopyArray = [];
        for ($i = 0; $i < 3; $i++) {

            $postCollectionCopy =
                factory(CollectionCopy::class)->create(
                    [
                        'isAvailable' => 1,
                        'idCollection' => factory(Collection::class)
                    ]

                )->toArray();

            $postCollectionCopyArray['collectionCopy'][$i] = $postCollectionCopy;
        }


        $postLoan = array_merge($postLoan, $postCollectionCopyArray);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertCreated();

        foreach ($postCollectionCopyArray['collectionCopy'] as $key => $postCollectionCopy) {


            $collectionCopy = CollectionCopy::find($postCollectionCopy['idCollectionCopy']);

            $this->assertFalse((bool) $collectionCopy->isAvailable);
        }
    }
}
