<?php

namespace App\Repositories;

use App\Http\Resources\CollectionType\CollectionTypeCollection;
use App\Http\Resources\CollectionType\CollectionTypeResource;
use App\Repositories\Interfaces\CollectionTypeRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\CollectionType;

class CollectionTypeRepository extends RepositoryModel implements CollectionTypeRepositoryInterface
{
	/**
	 *
	 *
	 * @param CollectionType collectionTypeModel
	 */
	public function __construct(CollectionType $collectionTypeModel)
	{
		$this->resourceName = 'Collection Type';
		parent::__construct($collectionTypeModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new CollectionTypeCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param CollectionType $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new CollectionTypeResource($model);
	}
}
