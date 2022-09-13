<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Resources\Json\Resource;

use Illuminate\Http\Resources\Json\ResourceCollection;

use Exception;

use Illuminate\Support\Facades\DB;

class CrudModelOperationsService
{

	/** 
	 * @var Model $model Base Model of Service 
	 */
	protected $model;

	/** @var string $resourceName Name of resource */
	public $resourceName;

	/** 
	 * @var mixed $responseFromTransaction 
	 */
	public $responseFromTransaction;


	/** 
	 * @var Exception $exceptionFromTransaction
	 */
	protected $exceptionFromTransaction;

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
	 * @return void
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
	 * @return void
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
	 *  @return void
	 */
	public function getAll()
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
	 * @return void
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
	protected function getResourceModel($model)
	{
		return new Resource($model);
	}

	/**
	 * @param $id
	 * @return void
	 */
	protected function getResourceCollectionModel()
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
