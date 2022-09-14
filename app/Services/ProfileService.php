<?php

namespace App\Services;

use App\Http\Resources\Profile\ProfileCollection;
use App\Http\Resources\Profile\ProfileResource;

use App\Services\CrudModelOperationsService;

use App\Models\Profile;

class ProfileService extends CrudModelOperationsService
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

		$this->getAll();

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
