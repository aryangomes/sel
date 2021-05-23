<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

use App\Repositories\Interfaces\RepositoryEloquentInterface as InterfacesRepositoryEloquentInterface;

use Illuminate\Http\Resources\Json\Resource;

use Illuminate\Http\Resources\Json\ResourceCollection;

use Exception;

use Illuminate\Support\Facades\DB;

class RepositoryModel implements InterfacesRepositoryEloquentInterface
{

	/** 
	 * @var Model $model Base Model of Repository 
	 */
	protected $model;

	/** @var Type $resourceName Name of resource */
	public $resourceName;

	/** 
	 * @var mixed $responseFromTransaction 
	 */
	public $responseFromTransaction;


	/** 
	 * @var Exception $exceptionFromTransaction
	 */
	public $exceptionFromTransaction;

	/** 
	 * @var boolean $transactionIsSuccessfully
	 */
	public $transactionIsSuccessfully;


	public function __construct(Model $model)
	{
		$this->model = $model;
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

	/**
	 * @param Model $model
	 * @return void
	 */
	public function delete($model)
	{
		$this->transactionIsSuccessfully = true;

		DB::beginTransaction();

		try {

			$this->responseFromTransaction = $model->delete();

			DB::commit();
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}

	/**
	 * @param $id
	 * @return Model
	 */
	public function findById($id)
	{
		$this->transactionIsSuccessfully = true;

		try {

			$this->responseFromTransaction = $this->model->find($id);
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}

	/**
	 * 
	 *  
	 */
	public function findAll()
	{
		$this->transactionIsSuccessfully = true;

		try {

			$this->responseFromTransaction = $this->model->all();
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}

	/**
	 * @param Model $model
	 * @param array $attributesForUpdate
	 * @return Model
	 */
	public function update(array $attributesForUpdate, Model $model)
	{

		$this->transactionIsSuccessfully = true;

		DB::beginTransaction();

		try {

			$this->responseFromTransaction = $model->update($attributesForUpdate);

			DB::commit();
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}


	/**
	 * @param Model $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new Resource($model);
	}

	/**
	 * @param $id
	 * @return ResourceCollection
	 */
	public function getResourceCollectionModel()
	{

		$this->transactionIsSuccessfully = true;

		try {

			$this->responseFromTransaction = new ResourceCollection($this->model->all());
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}

	protected function setTransactionExceptionResponse(Exception $exception)
	{
		$this->transactionIsSuccessfully = false;

		DB::rollBack();

		$this->exceptionFromTransaction = $exception;
	}
}
