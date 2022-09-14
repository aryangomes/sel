<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Lender;
use App\Http\Requests\Lender\LenderRegisterRequest;
use App\Http\Requests\Lender\LenderUpdateRequest;
use App\Http\Resources\LenderResource;
use App\Services\LenderService;
use Illuminate\Http\Response;

class LenderController extends ApiController
{


    /**
     *
     * @var Lender
     */
    private $lender;

    /**
     *
     * @var LenderService
     */
    private $lenderService;

    public function __construct(
        LenderService $lenderService,
        Lender $lender
    ) {

        $this->lenderService = $lenderService;
        $this->lender = $lender;
        $this->tablePermissions = 'lenders';
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
    public function store(LenderRegisterRequest $request)
    {
        $this->canPerformAction(
            $this->makeNameActionFromTable('store'),
            $this->lender
        );

        $requestValidated = $request->validated();

        $this->lenderService->create($requestValidated);

        if ($this->lenderService->transactionIsSuccessfully) {
            $lenderCreated =
                $this->lenderService->getResourceModel($this->lenderService->responseFromTransaction);

            $this->setSuccessResponse($lenderCreated, 'lender', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->lenderService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lender  $lender
     * @return \Illuminate\Http\Response
     */
    public function show(Lender $lender)
    {
        $this->lender = $lender;

        $this->canPerformAction(
            $this->makeNameActionFromTable('view'),
            $this->lender
        );

        return $this->lenderService->getResourceModel($lender);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lender  $lender
     * @return \Illuminate\Http\Response
     */
    public function edit(Lender $lender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lender  $lender
     * @return \Illuminate\Http\Response
     */
    public function update(LenderUpdateRequest $request, Lender $lender)
    {
        $this->lender = $lender;

        $this->canPerformAction(
            $this->makeNameActionFromTable('update'),
            $this->lender
        );

        $requestValidated = $request->validated();

        $this->lenderService->update($requestValidated, $this->lender);

        if ($this->lenderService->transactionIsSuccessfully) {

            $lenderUpdated =
                $this->lenderService->getResourceModel($this->lender);

            $this->setSuccessResponse($lenderUpdated, 'lender', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->lenderService->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lender  $lender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lender $lender)
    {
        $this->lender = $lender;

        $this->canPerformAction(
            $this->makeNameActionFromTable('delete'),
            $this->lender
        );

        $this->lenderService->delete($this->lender);

        if ($this->lenderService->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->lenderService->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->lenderService->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
