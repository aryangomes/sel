<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends ApiController
{

    /**
     *
     * @var User
     */
    private $user;

    /**
     *
     * @var UserService
     */
    private $userService;

    public function __construct(
        UserService $userService,
        User $user
    ) {
        $this->userService = $userService;
        $this->user = $user;
        $this->tablePermissions = 'users';
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
            $this->user
        );

        $this->userService->getResourceCollectionModel();

        if ($this->userService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->userService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->userService->exceptionFromTransaction);
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
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->user
        );


        $requestValidated = $request->validated();

        $this->userService->create($requestValidated);

        if ($this->userService->transactionIsSuccessfully) {
            $userCreated =
                $this->userService->getResourceModel($this->userService->responseFromTransaction);

            $this->setSuccessResponse($userCreated, 'user', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->userService->resourceName]
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

        $this->canPerformActionOrResourceBelongsToUser(
            $this->makeNameActionFromTable('view'),
            $this->user->id,
            $this->user
        );


        return $this->userService->getResourceModel($user);
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

        $this->canPerformActionOrResourceBelongsToUser(
            $this->makeNameActionFromTable('update'),
            $this->user->id,
            $this->user
        );


        $requestValidated = $request->validated();

        $this->userService->update($requestValidated, $this->user);

        if ($this->userService->transactionIsSuccessfully) {

            $userUpdated =
                $this->userService->getResourceModel($this->user);

            $this->setSuccessResponse($userUpdated, 'user', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->userService->resourceName]
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

        $this->canPerformActionOrResourceBelongsToUser(
            $this->makeNameActionFromTable('delete'),
            $this->user->id,
            $this->user
        );

        $this->userService->delete($this->user);

        if ($this->userService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->userService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->userService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
