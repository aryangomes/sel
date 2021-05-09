<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcquisitionType\RegisterAcquisitionTypeRequest;
use App\Http\Requests\AcquisitionType\UpdateAcquisitionTypeRequest;
use App\Models\AcquisitionType;
use App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AcquisitionTypeController extends Controller
{

    private $acquisitionTypeRepository;

    public function __construct(AcquisitionTypeRepositoryInterface $acquisitionTypeRepository)
    {
        $this->acquisitionTypeRepository = $acquisitionTypeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $acquisitionTypes = $this->acquisitionTypeRepository->getResourceCollectionModel();

            $this->setSuccessResponse($acquisitionTypes);
        } catch (\Exception $exception) {
            $this->logErrorFromException($exception);
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
    public function store(RegisterAcquisitionTypeRequest $request)
    {
        $this->authorize('create', new AcquisitionType());
        $acquisitionTypeWasCreated = false;


        try {
            $acquisitionTypeCreated = $this->acquisitionTypeRepository->create($request->all());

            $acquisitionTypeWasCreated = isset($acquisitionTypeCreated);
        } catch (\Exception $exception) {
            $this->logErrorFromException($exception);
        }

        if ($acquisitionTypeWasCreated) {

            DB::commit();

            $acquisitionTypeCreated =
                $this->acquisitionTypeRepository->getResourceModel($acquisitionTypeCreated);

            $this->setSuccessResponse($acquisitionTypeCreated, 'acquisitionType', Response::HTTP_CREATED);
        } else {

            DB::rollBack();

            $this->setErrorResponse('Acquisition Type create failed!', 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function show(AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function edit(AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAcquisitionTypeRequest $request, AcquisitionType $acquisitionType)
    {
        $this->authorize('update', $acquisitionType);

        $acquisitionTypeWasUpdated = false;

        $requestValidated = $request->validated();

        try {

            DB::beginTransaction();

            $acquisitionTypeWasUpdated = $this->acquisitionTypeRepository->update($requestValidated, $acquisitionType);
        } catch (\Exception $exception) {
            $this->logErrorFromException($exception);
        }

        if ($acquisitionTypeWasUpdated) {

            DB::commit();

            $acquisitionTypeUpdated =
                $this->acquisitionTypeRepository->getResourceModel($acquisitionType);

            $this->setSuccessResponse($acquisitionTypeUpdated, 'acquisitionType', Response::HTTP_OK);
        } else {

            DB::rollBack();

            $this->setErrorResponse('Acquisition Type update failed!', 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcquisitionType $acquisitionType)
    {
        $this->authorize('delete', $acquisitionType);

        $acquisitionTypeWasDeleted = false;
        try {

            DB::beginTransaction();

            $acquisitionTypeWasDeleted = $this->acquisitionTypeRepository->delete($acquisitionType);
        } catch (\Exception $exception) {
            $this->logErrorFromException($exception);
        }

        if ($acquisitionTypeWasDeleted) {

            DB::commit();

            $this->setSuccessResponse('Acquisition Type deleted successfully!');
        } else {

            DB::rollBack();

            $this->setErrorResponse('Acquisition Type deleted failed!', 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
