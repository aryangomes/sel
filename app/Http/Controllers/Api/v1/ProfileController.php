<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Profile\ProfileRegisterRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Http\Response;

class ProfileController extends ApiController
{

    /**
     *
     * @var Profile
     */
    private $profile;

    /**
     *
     * @var ProfileService
     */
    private $profileService;

    public function __construct(
        ProfileService $profileService,
        Profile $profile
    ) {
        $this->profileService = $profileService;
        $this->profile = $profile;
        $this->tablePermissions = 'profiles';
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
            $this->profile
        );
        $this->profileService->getResourceCollectionModel();

        if ($this->profileService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->profileService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->profileService->exceptionFromTransaction);
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
    public function store(ProfileRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->profile
        );

        $requestValidated = $request->validated();

        $this->profileService->create($requestValidated);

        if ($this->profileService->transactionIsSuccessfully) {
            $profileCreated =
                $this->profileService->getResourceModel($this->profileService->responseFromTransaction);

            $this->setSuccessResponse($profileCreated, 'profile', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->profileService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('view'),
            $this->profile
        );

        return $this->profileService->getResourceModel($profile);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, Profile $profile)
    {

        $this->profile = $profile;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->profile
        );

        $requestValidated = $request->validated();

        $this->profileService->update($requestValidated, $this->profile);

        if ($this->profileService->transactionIsSuccessfully) {

            $profileUpdated =
                $this->profileService->getResourceModel($this->profile);

            $this->setSuccessResponse($profileUpdated, 'profile', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->profileService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        $this->profile = $profile;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->profile
        );


        $this->profileService->delete($this->profile);

        if ($this->profileService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->profileService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->profileService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
