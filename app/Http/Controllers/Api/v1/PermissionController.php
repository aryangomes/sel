<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Permission\PermissionRegisterRequest;
use App\Http\Requests\Permission\PermissionUpdateRequest;
use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Illuminate\Http\Response;

class PermissionController extends ApiController
{

    private $permission;

    private $permissionRepository;

    public function __construct(
        PermissionRepositoryInterface $permissionRepository,
        Permission $permission
    ) {
        $this->authorizeResource(Permission::class, 'permission');
        $this->permissionRepository = $permissionRepository;
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->permissionRepository->getResourceCollectionModel();

        if ($this->permissionRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->permissionRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->permissionRepository->exceptionFromTransaction);
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
    public function store(PermissionRegisterRequest $request)
    {

        $requestValidated = $request->validated();

        $this->permissionRepository->create($requestValidated);

        if ($this->permissionRepository->transactionIsSuccessfully) {
            $permissionCreated =
                $this->permissionRepository->getResourceModel($this->permissionRepository->responseFromTransaction);

            $this->setSuccessResponse($permissionCreated, 'permission', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->permissionRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return $this->permissionRepository->getResourceModel($permission);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        $this->permission = $permission;

        $requestValidated = $request->validated();

        $this->permissionRepository->update($requestValidated, $this->permission);

        if ($this->permissionRepository->transactionIsSuccessfully) {

            $permissionUpdated =
                $this->permissionRepository->getResourceModel($this->permission);

            $this->setSuccessResponse($permissionUpdated, 'permission', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->permissionRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $this->permission = $permission;


        $this->permissionRepository->delete($this->permission);

        if ($this->permissionRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->permissionRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->permissionRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
