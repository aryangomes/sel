<?php

namespace Tests\Feature\Loan;

use App\Models\Collection;
use App\Models\CollectionCopy;
use App\Models\Loan;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileHasPermission;
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
        $this->generatePermissionsLoanProfileToUserNotAdmin(true);

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
        $this->generatePermissionsLoanProfileToUserNotAdmin(true);

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
        $this->generatePermissionsLoanProfileToUserNotAdmin();

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

    public function testRegisterLoanSuccessfullyWithUserOperatorNotAdmin()
    {
        $this->generatePermissionsLoanProfileToUserNotAdmin();

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

        $response->assertForbidden();

        $this->assertTrue((bool) $collectionCopy->isAvailable);
    }

    public function testRegisterLoanUnsuccessfullyWithCollectionCopyWithoutAvaible()
    {
        $this->generatePermissionsLoanProfileToUserNotAdmin();


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
        $this->generatePermissionsLoanProfileToUserNotAdmin();


        $postLoan = factory(Loan::class)->make()->toArray();

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertStatus(422);
    }

    public function testRegisterLoanUnsuccessfullyWithUserBlocked()
    {
        $this->generatePermissionsLoanProfileToUserNotAdmin();

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
        $this->generatePermissionsLoanProfileToUserNotAdmin();

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

    public function testRegisterLoanSuccessfullyWithMultipleCollectionCopiesFromOneCollection()
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

    public function testRegisterLoanSuccessfullyWithMultipleCollectionCopiesFromMutipleCollection()
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

        $collections = factory(Collection::class, 10)->create()
            ->each(function ($collection) {
                $collection->copies()->createMany(factory(CollectionCopy::class, 3)->make()->toArray());
            });

        foreach ($collections as $key => $collection) {

            $postCollectionCopyArray['collectionCopy'][$key] = $collection->copies->random();
        }

        $postLoan = array_merge($postLoan, $postCollectionCopyArray);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertCreated();

        foreach ($postCollectionCopyArray['collectionCopy'] as $key => $postCollectionCopy) {


            $collectionCopy = CollectionCopy::find($postCollectionCopy['idCollectionCopy']);

            $this->assertFalse((bool) $collectionCopy->isAvailable);
        }
    }

    public function testRegisterLoanUnsuccessfullyWithMultipleCollectionCopiesFromMutipleCollection()
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

        $collections = factory(Collection::class, 10)->create()
            ->each(function ($collection) {
                $collection->copies()->createMany(factory(CollectionCopy::class, 3)->make()->toArray());
            });

        foreach ($collections as $key => $collection) {

            $postCollectionCopyArray['collectionCopy'][$key] = $collection->copies->random();
        }


        $postLoan = array_merge($postLoan, $postCollectionCopyArray);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertCreated();

        foreach ($postCollectionCopyArray['collectionCopy'] as $key => $postCollectionCopy) {

            $collectionCopy = CollectionCopy::find($postCollectionCopy['idCollectionCopy']);

            $this->assertFalse((bool) $collectionCopy->isAvailable);
        }

        $postLoan = array_merge($postLoan, $postCollectionCopyArray);

        $response = $this->postJson($this->urlLoan, $postLoan);

        $response->assertStatus(422);
    }

    private function generatePermissionsLoanProfileToUserNotAdmin($canMadeAction = 0)
    {
        $crud =
            [
                'index',
                'view',
                'create',
                'store',
                'edit',
                'update',
                'delete',
            ];

        $permissions = [];

        foreach ($crud as $key => $value) {
            array_push($permissions, factory(Permission::class)->create(
                [
                    'permission' => "loans-{$value}"
                ]
            ));
        }

        foreach ($permissions as $key => $permission) {
            factory(ProfileHasPermission::class)->create([
                'idProfile' => $this->userProfile,
                'idPermission' => $permission->idPermission,
                'can' => $canMadeAction,
            ]);
        }
        $this->createAndAuthenticateTheUserNotAdmin([
            'idProfile' => $this->userProfile->idProfile
        ]);
    }
}
