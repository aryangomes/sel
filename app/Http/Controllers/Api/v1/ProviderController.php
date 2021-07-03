<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Provider\ProviderRegisterRequest;
use App\Http\Requests\Provider\ProviderUpdateRequest;
use App\Http\Resources\ProviderResource;
use App\Models\JuridicPerson;
use App\Models\NaturalPerson;
use App\Models\Provider;
use App\Repositories\Interfaces\ProviderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProviderController extends ApiController
{



    private $provider;

    private $providerRepository;

    public function __construct(
        ProviderRepositoryInterface $providerRepository,
        Provider $provider
    ) {

        $this->providerRepository = $providerRepository;
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
        //
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


        $this->providerRepository->create($requestValidated);



        if ($this->providerRepository->transactionIsSuccessfully) {
            $providerCreated =
                $this->providerRepository->getResourceModel($this->providerRepository->responseFromTransaction);

            $this->setSuccessResponse($providerCreated, 'provider', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->providerRepository->resourceName]
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

        return $this->providerRepository->getResourceModel($provider);
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

        $this->providerRepository->update($requestValidated, $this->provider);

        if ($this->providerRepository->transactionIsSuccessfully) {

            $providerUpdated =
                $this->providerRepository->getResourceModel($this->provider);

            $this->setSuccessResponse($providerUpdated, 'provider', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->providerRepository->resourceName]
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
