<?php

namespace App\Services;

use App\Actions\CrudModelOperations\Create;
use App\Actions\CrudModelOperations\Delete;
use App\Actions\CrudModelOperations\GetAll;
use App\Actions\CrudModelOperations\Update;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Resources\Json\Resource;

use Illuminate\Http\Resources\Json\ResourceCollection;
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


	/**
	 * 
	 * @var GetAll
	 */
	protected $getAll;

	/**
	 * 
	 * @var Create
	 */
	protected $create;

	/**
	 * 
	 * @var Update
	 */
	protected $update;

	/**
	 * 
	 * @var Delete
	 */
	protected $delete;


	public function __construct(Model $model)
	{
		$this->model = $model;
		$this->getAll = new GetAll($model);
		$this->create = new Create($model);
		$this->update = new Update($model);
		$this->delete = new Delete($model);
	}



	/**
	 * 
	 *  @return void
	 */
	public function getAll()
	{
		$this->transactionIsSuccessfully = true;

		try {
			$getAllAction = $this->getAll;
			$this->responseFromTransaction = $getAllAction();
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}


	/**
	 * @param string $id
	 * @return void
	 */
	public function findById(string $id)
	{
		$this->transactionIsSuccessfully = true;

		try {

			$this->responseFromTransaction = $this->model->find($id);
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}

	/**
	 * @param array $attributes
	 * @return void
	 */
	public function create(array $attributes)
	{
		$this->transactionIsSuccessfully = true;


		try {
			$createAction = $this->create;
			$this->responseFromTransaction = $createAction($attributes);
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


		try {

			$updateAction = $this->update;
			$this->responseFromTransaction = $updateAction($attributesForUpdate, $model);
		} catch (\Exception $exception) {

			$this->setTransactionExceptionResponse($exception);
		}
	}

	/**
	 * @param Model $model
	 * @return void
	 */
	public function delete(Model $model)
	{
		$this->transactionIsSuccessfully = true;

		try {

			$deleteAction = $this->delete;
			$this->responseFromTransaction = $deleteAction($model);
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

	protected function setTransactionExceptionResponse(\Exception $exception)
	{
		$this->transactionIsSuccessfully = false;

		DB::rollBack();

		$this->exceptionFromTransaction = $exception;
	}
}
