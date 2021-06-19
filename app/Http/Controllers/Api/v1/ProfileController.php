<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Profile\ProfileRegisterRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\Profile;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Response;

class ProfileController extends ApiController
{

    private $profile;

    private $profileRepository;

    public function __construct(
        ProfileRepositoryInterface $profileRepository,
        Profile $profile
    ) {
        $this->authorizeResource(Profile::class, 'profile');
        $this->profileRepository = $profileRepository;
        $this->profile = $profile;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->profileRepository->getResourceCollectionModel();

        if ($this->profileRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->profileRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->profileRepository->exceptionFromTransaction);
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

        $requestValidated = $request->validated();

        $this->profileRepository->create($requestValidated);

        if ($this->profileRepository->transactionIsSuccessfully) {
            $profileCreated =
                $this->profileRepository->getResourceModel($this->profileRepository->responseFromTransaction);

            $this->setSuccessResponse($profileCreated, 'profile', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->profileRepository->resourceName]
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
        return $this->profileRepository->getResourceModel($profile);
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

        $requestValidated = $request->validated();

        $this->profileRepository->update($requestValidated, $this->profile);

        if ($this->profileRepository->transactionIsSuccessfully) {

            $profileUpdated =
                $this->profileRepository->getResourceModel($this->profile);

            $this->setSuccessResponse($profileUpdated, 'profile', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->profileRepository->resourceName]
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


        $this->profileRepository->delete($this->profile);

        if ($this->profileRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->profileRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->profileRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
