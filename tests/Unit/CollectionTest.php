<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $urlCollection;

    /**
     * @override
     */
    public function setUp(): void
    {
        $this->urlCollection = "{$this->url}collections";
        parent::setUp();
    }

    /**
     * @override
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testViewAllCollectionDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collection = factory(Collection::class)->create();


        Passport::actingAs($userAdmin);

        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson($this->urlCollection);

        $response->assertOk();


        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson($this->urlCollection);

        $response->assertOk();
    }

    public function testViewCollectionDataSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collection = factory(Collection::class)->create();


        Passport::actingAs($userAdmin);

        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->getJson($this->urlCollection);

        $response->assertOk();


        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $this->assertAuthenticatedAs($user, 'api');

        $response = $this->getJson($this->urlWithParameter(
            $this->urlCollection,
            $collection->idCollection
        ));

        $response->assertOk();
    }


    public function testRegisterCollectionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollection = factory(Collection::class)->make()->toArray();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollection, $postCollection);

        $response->assertCreated();
    }

    public function testRegisterCollectionUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $postCollection = [
            'title' => $this->faker->sentence(500),
            'author' => "{$this->faker->firstName} {$this->faker->lastName}",
            'cdd' => $this->faker->numerify('######################'),
            'cdu' =>  $this->faker->numerify('#########################'),
            'isbn' => $this->faker->isbn13(120),
            'publisherCompany' => $this->faker->company,
            'idCollectionType' => factory(CollectionType::class),
            'idCollectionCategory' => factory(CollectionCategory::class),
            'idAcquisition' => factory(Acquisition::class),
        ];



        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->postJson($this->urlCollection, $postCollection);

        $response->assertStatus(422);
    }

    public function testUpdateCollectionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collection = factory(Collection::class)->create();

        $dataUpdateForCollection = [
            'title' => $this->faker->sentence(5),
            'author' => "{$this->faker->firstName} {$this->faker->lastName}",
            'cdd' => $this->faker->numerify('#########'),
            'cdu' =>  $this->faker->numerify('#########'),
            'isbn' => $this->faker->isbn13(10),
            'publisherCompany' => $this->faker->company,
            'idCollectionType' => factory(CollectionType::class),
            'idCollectionCategory' => factory(CollectionCategory::class),
            'idAcquisition' => factory(Acquisition::class),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollection, $collection->idCollection),
            $dataUpdateForCollection
        );

        $response->assertOk();
        $getCollection = $response->getData()->collection;

        $this->assertEquals($getCollection->title, $dataUpdateForCollection['title']);
        $this->assertEquals($getCollection->publisherCompany, $dataUpdateForCollection['publisherCompany']);
        $this->assertEquals($getCollection->isbn, $dataUpdateForCollection['isbn']);
    }

    public function testUpdateCollectionUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collection = factory(Collection::class)->create();

        $dataUpdateForCollection = [
            'author' => "{$this->faker->firstName} {$this->faker->lastName}",
            'cdd' => $this->faker->numerify('#########'),
            'cdu' =>  $this->faker->numerify('#########'),
            'isbn' => $this->faker->isbn13(10),
        ];

        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->putJson(
            $this->urlWithParameter($this->urlCollection, $collection->idCollection),
            $dataUpdateForCollection
        );

        $response->assertStatus(422);
    }


    public function testDeleteCollectionSuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collection = factory(Collection::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollection, $collection->idCollection)
        );

        $response->assertOk();

        $collectionWasDeleted = isset(Collection::withTrashed()->find($collection->idCollection)->deleted_at);

        $this->assertTrue($collectionWasDeleted);
    }

    public function testDeleteCollectionUnsuccessfully()
    {
        $userAdmin = factory(User::class)->create(
            [
                'isAdmin' => 1
            ]
        );

        $collection = factory(Collection::class)->create();


        Passport::actingAs($userAdmin);
        $this->assertAuthenticatedAs($userAdmin, 'api');

        $response = $this->deleteJson(
            $this->urlWithParameter($this->urlCollection)
        );

        $response->assertStatus(405);
    }
}
