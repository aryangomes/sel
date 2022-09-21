<?php

namespace App\Services;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;

use App\Services\CrudModelOperationsService;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

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
	 * @param array $attributes
	 * @return void
	 */
	public function create(array $attributes)
	{
		$this->transactionIsSuccessfully = true;




		try {
			$hashedPassword = Hash::make(
				key_exists('password', $attributes) ?
					$attributes['password'] : config('user.default_password_not_admin')
			);
			$attributes = array_merge($attributes, [
				'password' => $hashedPassword
			]);

			$createAction = $this->create;
			$this->responseFromTransaction = $createAction($attributes);
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}

	/**
	 * @param User $user
	 * @param array $attributesForUpdate
	 * @return void
	 */
	public function update(array $attributesForUpdate, Model $user)
	{

		$this->transactionIsSuccessfully = true;

		try {
			if (key_exists('password', $attributesForUpdate)) {
				$user->setNewPassword($attributesForUpdate['password']);
				$attributesForUpdate = array_merge(
					$attributesForUpdate,
					[
						'password' => $user->password
					]
				);
			}
			$updateAction = $this->update;
			$this->responseFromTransaction = $updateAction($attributesForUpdate, $user);
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
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
