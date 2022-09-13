<?php

namespace App\Services;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;

use App\Services\CrudModelOperationsService;

use App\Models\User;

class UserService extends CrudModelOperationsService
{
	/**
	 *
	 *
	 * @param User userModel
	 */
	public function __construct(User $userModel)
	{
		$this->resourceName = 'User';
		parent::__construct($userModel);
	}


	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->getAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new UserCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param User $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new UserResource($model);
	}
}
