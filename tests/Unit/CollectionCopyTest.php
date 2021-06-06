<?php

namespace Tests\Unit;

use App\Models\Collection;
use App\Models\CollectionCopy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CollectionCopyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $urlCollectionCopy;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlCollectionCopy = "{$this->url}collectionCopies";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testViewAllCollectionCopyDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCopy = factory(CollectionCopy::class)->create();


        Passport::actingAs($userAdmin);

        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson($this->urlCollectionCopy);

        $response->assertOk();


        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson($this->urlCollectionCopy);

        $response->assertOk();
    }


    public function testRegisterCollectionCopySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollectionCopy = factory(CollectionCopy::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollectionCopy, $postCollectionCopy);

        $response->assertCreated();
    }

    public function testRegisterCollectionCopyUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollectionCopy = factory(CollectionCopy::class)->make(
            [
                'reference' => 83471298043,
                'isAvailable' => 'yes',
            ]
        )->toArray();

        Passport::actingAs($userAdmin);

        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollectionCopy, $postCollectionCopy);

        $response->assertStatus(422);
    }

    public function testUpdateCollectionCopySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCopy = factory(CollectionCopy::class)->create();

        $dataUpdateForCollectionCopy = [
            'reference' => $this->faker->numerify('Loc. ###-###-###'),
            'isAvailable' => $this->faker->boolean,
            'idCollection' => factory(Collection::class),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollectionCopy, $collectionCopy->idCollectionCopy),
            $dataUpdateForCollectionCopy
        );

        $response->assertOk();
        $getCollectionCopy = $response->getData()->collectionCopy;

        $this->assertEquals($getCollectionCopy->reference, $dataUpdateForCollectionCopy['reference']);
        $this->assertEquals($getCollectionCopy->isAvailable, $dataUpdateForCollectionCopy['isAvailable']);
    }

    public function testUpdateCollectionCopyUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCopy = factory(CollectionCopy::class)->create();

        $dataUpdateForCollectionCopy = [];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollectionCopy, $collectionCopy->idCollectionCopy),
            $dataUpdateForCollectionCopy
        );

        $response->assertStatus(422);
    }


    public function testDeleteCollectionCopySuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCopy = factory(CollectionCopy::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollectionCopy, $collectionCopy->idCollectionCopy)
        );

        $response->assertOk();

        $collectionCopyWasDeleted = isset(CollectionCopy::withTrashed()->find($collectionCopy->idCollectionCopy)->deleted_at);

        $this->assertTrue($collectionCopyWasDeleted);
    }

    public function testDeleteCollectionCopyUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collectionCopy = factory(CollectionCopy::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollectionCopy)
        );

        $response->assertStatus(405);
    }
}
