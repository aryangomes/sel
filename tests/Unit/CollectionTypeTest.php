<?php

namespace Tests\Unit;

use App\Models\CollectionType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class CollectionTypeTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    protected $urlCollectionType;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlCollectionType = "{$this->url}collectionTypes";
        parent::setUp();

        $this->generateProfile();

        $this->generateProfilePermissions('collection_types');
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testViewAllCollectionTypeDataSuccessfully()
    {


        $collectionType = factory(CollectionType::class)->create();


        $this->createAndAuthenticateTheAdminUser();

        $response = $this->getJson($this->urlCollectionType);

        $response->assertOk();

        $this->createAndAuthenticateTheUserNotAdmin(
            [

                'idProfile' => $this->userProfile,
            ]
        );

        $response = $this->getJson($this->urlCollectionType);

        $response->assertOk();
    }


    public function testRegisterCollectionTypeSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollectionType = factory(CollectionType::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollectionType, $postCollectionType);

        $response->assertCreated();
    }

    public function testRegisterCollectionTypeUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollectionType = factory(CollectionType::class)->make()->toArray();


        unset($postCollectionType['type']);

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollectionType, $postCollectionType);

        $response->assertStatus(422);
    }

    public function testUpdateCollectionTypeSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionType = factory(CollectionType::class)->create();

        $dataUpdateForCollectionType = [
            'type' => $this->faker->word,
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollectionType, $collectionType->idCollectionType),
            $dataUpdateForCollectionType
        );

        $response->assertOk();
        $getCollectionType = $response->getData()->collectionType;

        $this->assertEquals($getCollectionType->type, $dataUpdateForCollectionType['type']);
    }

    public function testUpdateCollectionTypeUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionType = factory(CollectionType::class)->create();

        $dataUpdateForCollectionType = [];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollectionType, $collectionType->idCollectionType),
            $dataUpdateForCollectionType
        );

        $response->assertStatus(422);
    }


    public function testDeleteCollectionTypeSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionType = factory(CollectionType::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollectionType, $collectionType->idCollectionType)
        );

        $response->assertOk();

        $collectionTypeWasDeleted = isset(CollectionType::withTrashed()->find($collectionType->idCollectionType)->deleted_at);

        $this->assertTrue($collectionTypeWasDeleted);
    }

    public function testDeleteCollectionTypeUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionType = factory(CollectionType::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollectionType)
        );

        $response->assertStatus(405);
    }
}
