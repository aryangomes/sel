<?php

namespace Tests\Feature\Loan;

use App\Models\Loan\Loan;
use App\Models\Permission;
use App\Models\ProfileHasPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;
use Tests\TestCase;

class BaseLoanTest extends BaseTest
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

    public function testBase()
    {
        $this->assertTrue(true);
    }


    protected function generatePermissionsLoanProfileToUserNotAdmin($canMadeAction = 0)
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

    protected function getLoanFromResponse($response)
    {
        if ($response == null) {
            return null;
        }

        $loan = Loan::find($response->getData()->loan->idLoan);

        return $loan;
    }
}
