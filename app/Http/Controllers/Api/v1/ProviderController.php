<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Provider\ProviderRegisterRequest;
use App\Http\Requests\Provider\ProviderUpdateRequest;
use App\Http\Resources\ProviderResource;
use App\Models\JuridicPerson;
use App\Models\NaturalPerson;
use App\Models\Provider;
use App\Services\ProviderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProviderController extends ApiController
{

    /**
     *
     * @var Provider
     */
    private $provider;

    /**
     *
     * @var ProviderService
     */
    private $providerService;

    public function __construct(
        ProviderService $providerService,
        Provider $provider
    ) {

        $this->providerService = $providerService;
        $this->provider = $provider;
        $this->tablePermissions = 'providers';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('index'),
            $this->provider
        );

        $this->providerService->getResourceCollectionModel();

        if ($this->providerService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->providerService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->providerService->exceptionFromTransaction);
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
    public function store(ProviderRegisterRequest $request)
    {

        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->provider
        );

        $requestValidated = $request->validated();


        $this->providerService->create($requestValidated);



        if ($this->providerService->transactionIsSuccessfully) {
            $providerCreated =
                $this->providerService->getResourceModel($this->providerService->responseFromTransaction);

            $this->setSuccessResponse($providerCreated, 'provider', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->providerService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        $this->provider = $provider;

        $this->canPerformAction(
            $this->makeNameActionFromTable('view'),
            $this->provider
        );

        return $this->providerService->getResourceModel($provider);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderUpdateRequest $request, Provider $provider)
    {
        $this->provider = $provider;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->provider
        );

        $requestValidated = $request->validated();

        $this->providerService->update($requestValidated, $this->provider);

        if ($this->providerService->transactionIsSuccessfully) {

            $providerUpdated =
                $this->providerService->getResourceModel($this->provider);

            $this->setSuccessResponse($providerUpdated, 'provider', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->providerService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        $providerWasDeleted = false;

        $this->authorize('delete', $provider);

        try {
            DB::beginTransaction();

            if (isset($provider->naturalPerson)) {
                $provider->naturalPerson->delete();
            } elseif (isset($provider->juridicPerson)) {
                $provider->juridicPerson->delete();
            }

            $providerWasDeleted = $provider->delete();
        } catch (\Exception $exception) {
            $this->logErrorFromException($exception);
        }


        if ($providerWasDeleted) {
            DB::commit();
            $this->setSuccessResponse('Provider deleted successfully', 'success', Response::HTTP_OK);
        } else {
            DB::rollBack();
            $this->setErrorResponse('Provider deleted failed', 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
