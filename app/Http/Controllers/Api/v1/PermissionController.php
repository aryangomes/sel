<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Permission\PermissionRegisterRequest;
use App\Http\Requests\Permission\PermissionUpdateRequest;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Response;

class PermissionController extends ApiController
{

    /**
     *
     * @var Permission
     */
    private $permission;

    /**
     *
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(
        PermissionService $permissionService,
        Permission $permission
    ) {
        $this->permissionService = $permissionService;
        $this->permission = $permission;
        $this->tablePermissions = 'permissions';
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
            $this->permission
        );

        $this->permissionService->getResourceCollectionModel();

        if ($this->permissionService->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->permissionService->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->permissionService->exceptionFromTransaction);
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
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->permission
        );

        $requestValidated = $request->validated();

        $this->permissionService->create($requestValidated);

        if ($this->permissionService->transactionIsSuccessfully) {
            $permissionCreated =
                $this->permissionService->getResourceModel($this->permissionService->responseFromTransaction);

            $this->setSuccessResponse($permissionCreated, 'permission', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->permissionService->resourceName]
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
        $this->canPerformAction(
            $this->makeNameActionFromTable('view'),
            $this->permission
        );

        return $this->permissionService->getResourceModel($permission);
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

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->permission
        );

        $requestValidated = $request->validated();

        $this->permissionService->update($requestValidated, $this->permission);

        if ($this->permissionService->transactionIsSuccessfully) {

            $permissionUpdated =
                $this->permissionService->getResourceModel($this->permission);

            $this->setSuccessResponse($permissionUpdated, 'permission', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->permissionService->resourceName]
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

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->permission
        );

        $this->permissionService->delete($this->permission);

        if ($this->permissionService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->permissionService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->permissionService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
