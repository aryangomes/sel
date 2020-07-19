<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\RegisterProviderRequest;
use App\Http\Requests\Provider\UpdateProviderRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{
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
    public function store(RegisterProviderRequest $request)
    {
        $requestValidated = $request->validated();

        $providerWasCreated = false;

        $this->authorize('create', new Provider());

        try {
            DB::beginTransaction();

            $providerCreated = Provider::create($requestValidated);

            $providerWasCreated = isset($providerCreated);
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        if ($providerWasCreated) {
            DB::commit();
            $providerResource = $this->getProviderResource($providerCreated->idProvider);
            $this->setSuccessResponse($providerResource, 'provider',  Response::HTTP_CREATED);
        } else {
            DB::rollBack();
            $this->setErrorResponse();
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
        $this->authorize('view', $provider);

        $providerResource = $this->getProviderResource($provider->idProvider);

        return $providerResource;
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
    public function update(UpdateProviderRequest $request, Provider $provider)
    {
        $requestValidated = $request->validated();

        $providerWasUpdated = false;

        $this->authorize('update', $provider);

        try {
            DB::beginTransaction();

            $providerWasUpdated = $provider->update($requestValidated);
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        if ($providerWasUpdated) {
            DB::commit();
            $providerResource = $this->getProviderResource($provider->idProvider);
            $this->setSuccessResponse($providerResource, 'provider', 200);
        } else {
            DB::rollBack();
            $this->setErrorResponse();
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

    /**
     * 
     * @param int $idProvider
     * @return  ProviderResource
     * 
     */
    private function getProviderResource($idProvider)
    {
        $providerResource = new ProviderResource(Provider::find($idProvider));
        return $providerResource;
    }
}
