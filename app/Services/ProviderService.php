<?php

namespace App\Services;

use App\Http\Resources\Provider\ProviderCollection;
use App\Http\Resources\Provider\ProviderResource;
use App\Services\CrudModelOperationsService;
use App\Models\JuridicPerson;
use App\Models\NaturalPerson;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;

class ProviderService extends CrudModelOperationsService
{

	private $typesOfProvider = [
		1 => 'naturalPerson',
		2 => 'juridicPerson'
	];

	/**
	 *
	 *
	 * @param Provider providerModel
	 */
	public function __construct(Provider $providerModel)
	{
		$this->resourceName = 'Provider';
		parent::__construct($providerModel);
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

			if (array_key_exists('cpf', $attributes)) {
				$this->createPersonProvider(
					$this->typesOfProvider[1],
					[
						'idProvider' => $this->responseFromTransaction->idProvider,
						'cpf' => $attributes['cpf'],
					]
				);
			} else {
				$this->createPersonProvider(
					$this->typesOfProvider[2],
					[
						'idProvider' => $this->responseFromTransaction->idProvider,
						'cnpj' => $attributes['cnpj'],
					]
				);
			}



			DB::commit();
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

			$this->responseFromTransaction = new ProviderCollection($this->responseFromTransaction);
		}
	}

	/**
	 * @param Provider $model
	 * @return Resource
	 */
	public function getResourceModel($model)
	{
		return new ProviderResource($model);
	}


	private function createPersonProvider($typeOfProvider, $dataTypeOfProvider)
	{
		$personCreated = null;

		switch ($typeOfProvider) {
			case $this->typesOfProvider[1]:
				$personCreated = $this->createNaturalPerson($dataTypeOfProvider);
				break;

			case $this->typesOfProvider[2]:
				$personCreated = $this->createJuridicPerson($dataTypeOfProvider);
				break;
		}

		return $personCreated;
	}

	private function createNaturalPerson($dataNaturalPerson)
	{
		$naturalPersonCreated = NaturalPerson::create($dataNaturalPerson);

		return $naturalPersonCreated;
	}

	private function createJuridicPerson($dataJuridicPerson)
	{
		$juridicPersonCreated = JuridicPerson::create($dataJuridicPerson);

		return $juridicPersonCreated;
	}
}
