<?php

namespace App\Repositories;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Repositories\Interfaces\UserRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\User;

class UserRepository extends RepositoryModel implements UserRepositoryInterface
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

		$this->findAll();

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
