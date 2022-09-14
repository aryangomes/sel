<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\Acquisition\AcquisitionCollection;
use App\Http\Resources\Acquisition\AcquisitionResource;
use App\Models\Acquisition;

class AcquisitionService extends CrudModelOperationsService
{

    /**
     * @param Acquisition acquisitionModel
     */
    public function __construct(Acquisition $acquisitionModel)
    {
        $this->resourceName = 'Acquisition';
        parent::__construct($acquisitionModel);
    }


    /**
     * 
     * @return ResourceCollection
     */
    public function getResourceCollectionModel()
    {
        $this->getAll();
        return new AcquisitionCollection($this->responseFromTransaction);
    }

    /**
     * @param Acquisition $model
     * @return Resource
     */
    public function getResourceModel($model)
    {
        return new AcquisitionResource($model);
    }
}
