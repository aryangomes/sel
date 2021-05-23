<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acquisition\RegisterAcquisitionRequest;
use App\Http\Requests\Acquisition\UpdateAcquisitionRequest;
use App\Models\Acquisition;
use App\Repositories\Interfaces\AcquisitionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AcquisitionController extends Controller
{

    private $acquisition;

    private $acquisitionRepository;

    public function __construct(
        AcquisitionRepositoryInterface $acquisitionRepository,
        Acquisition $acquisition
    ) {
        $this->acquisitionRepository = $acquisitionRepository;
        $this->acquisition = $acquisition;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->acquisitionRepository->getResourceCollectionModel();

        if ($this->acquisitionRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->acquisitionRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->acquisitionRepository->exceptionFromTransaction);
            $this->setErrorResponse();
        }

        return $this->responseWithJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterAcquisitionRequest $request)
    {
        $this->authorize('create', $this->acquisition);

        $requestValidated = $request->validated();

        $this->acquisitionRepository->create($requestValidated);

        if ($this->acquisitionRepository->transactionIsSuccessfully) {
            $acquisitionCreated =
                $this->acquisitionRepository->getResourceModel($this->acquisitionRepository->responseFromTransaction);

            $this->setSuccessResponse($acquisitionCreated, 'acquisition', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->acquisitionRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function show(Acquisition $acquisition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function edit(Acquisition $acquisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAcquisitionRequest $request, Acquisition $acquisition)
    {
        $this->acquisition = $acquisition;

        $this->authorize('update',  $this->acquisition);

        $requestValidated = $request->validated();

        $this->acquisitionRepository->update($requestValidated, $this->acquisition);

        if ($this->acquisitionRepository->transactionIsSuccessfully) {

            $acquisitionUpdated =
                $this->acquisitionRepository->getResourceModel($this->acquisition);

            $this->setSuccessResponse($acquisitionUpdated, 'acquisition', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->acquisitionRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acquisition $acquisition)
    {
        $this->acquisition = $acquisition;

        $this->authorize('delete',  $this->acquisition);

        $this->acquisitionRepository->delete($this->acquisition);

        if ($this->acquisitionRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->acquisitionRepository->resourceName]
                ),
                Controller::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->acquisitionRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
