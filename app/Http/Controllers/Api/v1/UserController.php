<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

use Illuminate\Http\Response;

class UserController extends ApiController
{

    private $user;

    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        User $user
    ) {
        $this->userRepository = $userRepository;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->userRepository->getResourceCollectionModel();

        if ($this->userRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->userRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->userRepository->exceptionFromTransaction);
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
    public function store(UserRegisterRequest $request)
    {
        $this->authorize('create', $this->user);

        $requestValidated = $request->validated();

        $this->userRepository->create($requestValidated);

        if ($this->userRepository->transactionIsSuccessfully) {
            $userCreated =
                $this->userRepository->getResourceModel($this->userRepository->responseFromTransaction);

            $this->setSuccessResponse($userCreated, 'user', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->userRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->user = $user;
        $this->authorize('view', $this->user);

        return $this->userRepository->getResourceModel($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $this->user = $user;

        $this->authorize('update',  $this->user);

        $requestValidated = $request->validated();

        $this->userRepository->update($requestValidated, $this->user);

        if ($this->userRepository->transactionIsSuccessfully) {

            $userUpdated =
                $this->userRepository->getResourceModel($this->user);

            $this->setSuccessResponse($userUpdated, 'user', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->userRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->user = $user;

        $this->authorize('delete',  $this->user);

        $this->userRepository->delete($this->user);

        if ($this->userRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->userRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->userRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
