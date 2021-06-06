<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\UserProfile\UserProfileRegisterRequest;
use App\Http\Requests\UserProfile\UserProfileUpdateRequest;
use App\Models\UserProfile;
use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use Illuminate\Http\Response;

class UserProfileController extends ApiController
{

    private $userProfile;

    private $userProfileRepository;

    public function __construct(
        UserProfileRepositoryInterface $userProfileRepository,
        UserProfile $userProfile
    ) {
        $this->authorizeResource(UserProfile::class, 'userProfile');
        $this->userProfileRepository = $userProfileRepository;
        $this->userProfile = $userProfile;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->userProfileRepository->getResourceCollectionModel();

        if ($this->userProfileRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->userProfileRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->userProfileRepository->exceptionFromTransaction);
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
    public function store(UserProfileRegisterRequest $request)
    {

        $requestValidated = $request->validated();

        $this->userProfileRepository->create($requestValidated);

        if ($this->userProfileRepository->transactionIsSuccessfully) {
            $userProfileCreated =
                $this->userProfileRepository->getResourceModel($this->userProfileRepository->responseFromTransaction);

            $this->setSuccessResponse($userProfileCreated, 'userProfile', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->userProfileRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function show(UserProfile $userProfile)
    {
        return $this->userProfileRepository->getResourceModel($userProfile);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(UserProfile $userProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(UserProfileUpdateRequest $request, UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;

        $requestValidated = $request->validated();

        $this->userProfileRepository->update($requestValidated, $this->userProfile);

        if ($this->userProfileRepository->transactionIsSuccessfully) {

            $userProfileUpdated =
                $this->userProfileRepository->getResourceModel($this->userProfile);

            $this->setSuccessResponse($userProfileUpdated, 'userProfile', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->userProfileRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;


        $this->userProfileRepository->delete($this->userProfile);

        if ($this->userProfileRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->userProfileRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->userProfileRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
