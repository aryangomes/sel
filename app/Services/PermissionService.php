<?php

namespace App\Services;

use App\Http\Resources\Permission\PermissionCollection;
use App\Http\Resources\Permission\PermissionResource;

use App\Services\CrudModelOperationsService;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionService extends CrudModelOperationsService
{
	/**
	 *
	 *
	 * @param Permission profilePermissionModel
	 */
	public function __construct(Permission $profilePermissionModel)
	{
		$this->resourceName = 'Permission';
		parent::__construct($profilePermissionModel);
	}


	/**
	 * 
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->getAll();

		if ($this->transactionIsSuccessfully) {

			$this->responseFromTransaction = new PermissionCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param Permission $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new PermissionResource($model);
	}


	/**
	 * @param array $attributes
	 * @return Model
	 */
	public function create(array $attributes)
	{
		$this->transactionIsSuccessfully = true;

		DB::beginTransaction();

		try {

			$this->responseFromTransaction = $this->model->create($attributes);

			DB::commit();
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}
}
