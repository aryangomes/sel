<?php

namespace App\Repositories;

use App\Http\Resources\Profile\ProfileCollection;
use App\Http\Resources\Profile\ProfileResource;
use App\Repositories\Interfaces\ProfileRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\Profile;

class ProfileRepository extends RepositoryModel implements ProfileRepositoryInterface
{
	/**
	 *
	 *
	 * @param Profile profileModel
	 */
	public function __construct(Profile $profileModel)
	{
		$this->resourceName = 'Profile';
		parent::__construct($profileModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new ProfileCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param Profile $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new ProfileResource($model);
	}
}
