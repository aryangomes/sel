<?php

namespace App\Repositories;

use App\Http\Resources\Collection\CollectionCollection;
use App\Http\Resources\Collection\CollectionResource;
use App\Repositories\Interfaces\CollectionRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\Collection;

class CollectionRepository extends RepositoryModel implements CollectionRepositoryInterface
{
	/**
	 *
	 *
	 * @param Collection collectionModel
	 */
	public function __construct(Collection $collectionModel)
	{
		$this->resourceName = 'Collection';
		parent::__construct($collectionModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new CollectionCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param CollectionType $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new CollectionResource($model);
	}
}
