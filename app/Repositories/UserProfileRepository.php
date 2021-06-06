<?php

namespace App\Repositories;

use App\Http\Resources\UserProfile\UserProfileCollection;
use App\Http\Resources\UserProfile\UserProfileResource;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;

use App\Repositories\RepositoryModel;

use App\Models\UserProfile;

class UserProfileRepository extends RepositoryModel implements UserProfileRepositoryInterface
{
	/**
	 *
	 *
	 * @param UserProfile userProfileModel
	 */
	public function __construct(UserProfile $userProfileModel)
	{
		$this->resourceName = 'UserProfile';
		parent::__construct($userProfileModel);
	}

	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->findAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new UserProfileCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param UserProfile $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new UserProfileResource($model);
	}
}
