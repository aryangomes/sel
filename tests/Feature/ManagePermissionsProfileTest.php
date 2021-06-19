<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Permission;
use App\Models\PermissionAction;

class ManagePermissionsProfileTest extends TestCase
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
