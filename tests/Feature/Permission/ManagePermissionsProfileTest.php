<?php

namespace Tests\Feature\Permission;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\BaseTest;
use App\Models\Permission;
use App\Models\PermissionAction;

class ManagePermissionsProfileTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    protected $urlPermission;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlPermission = "{$this->url}permissions";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCreatePermissionToProfileSuccessfully()
    {
        $this->createAndAuthenticateTheAdminUser();

        $postPermission = factory(Permission::class)->make(
            [
                'permission' => PermissionAction::$COMMOM_ACTIONS[3],
            ]
        )->toArray();

        $response = $this->postJson($this->urlPermission, $postPermission);


        $response->assertCreated();
    }
}
