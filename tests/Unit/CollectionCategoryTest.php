<?php

namespace Tests\Unit;

use App\Models\CollectionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\BaseTest;

class CollectionCategoryTest extends BaseTest
{
    use RefreshDatabase, WithFaker;

    protected $urlCollectionCategory;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlCollectionCategory = "{$this->url}collectionCategories";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testViewAllCollectionCategoryDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCategory = factory(CollectionCategory::class)->create();


        Passport::actingAs($userAdmin);

        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson($this->urlCollectionCategory);

        $response->assertOk();


        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson($this->urlCollectionCategory);

        $response->assertOk();
    }


    public function testRegisterCollectionCategorySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollectionCategory = factory(CollectionCategory::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollectionCategory, $postCollectionCategory);

        $response->assertCreated();
    }

    public function testRegisterCollectionCategoryUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollectionCategory = factory(CollectionCategory::class)->make()->toArray();


        unset($postCollectionCategory['type']);

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollectionCategory, $postCollectionCategory);

        $response->assertStatus(422);
    }

    public function testUpdateCollectionCategorySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCategory = factory(CollectionCategory::class)->create();

        $dataUpdateForCollectionCategory = [
            'type' => $this->faker->word,
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollectionCategory, $collectionCategory->idCollectionCategory),
            $dataUpdateForCollectionCategory
        );

        $response->assertOk();
        $getCollectionCategory = $response->getData()->collectionCategory;

        $this->assertEquals($getCollectionCategory->type, $dataUpdateForCollectionCategory['type']);
    }

    public function testUpdateCollectionCategoryUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCategory = factory(CollectionCategory::class)->create();

        $dataUpdateForCollectionCategory = [];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollectionCategory, $collectionCategory->idCollectionCategory),
            $dataUpdateForCollectionCategory
        );

        $response->assertStatus(422);
    }


    public function testDeleteCollectionCategorySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCategory = factory(CollectionCategory::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollectionCategory, $collectionCategory->idCollectionCategory)
        );

        $response->assertOk();

        $collectionCategoryWasDeleted = isset(CollectionCategory::withTrashed()->find($collectionCategory->idCollectionCategory)->deleted_at);

        $this->assertTrue($collectionCategoryWasDeleted);
    }

    public function testDeleteCollectionCategoryUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCategory = factory(CollectionCategory::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollectionCategory)
        );

        $response->assertStatus(405);
    }
}
