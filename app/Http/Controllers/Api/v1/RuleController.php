<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Rule\RuleRegisterRequest;
use App\Http\Requests\Rule\RuleUpdateRequest;
use App\Models\Rule;
use App\Repositories\Interfaces\RuleRepositoryInterface;
use Illuminate\Http\Response;

class RuleController extends ApiController
{

    private $rule;

    private $ruleRepository;

    public function __construct(
        RuleRepositoryInterface $ruleRepository,
        Rule $rule
    ) {
        $this->authorizeResource(Rule::class, 'rule');
        $this->ruleRepository = $ruleRepository;
        $this->rule = $rule;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->ruleRepository->getResourceCollectionModel();

        if ($this->ruleRepository->transactionIsSuccessfully) {

            $this->setSuccessResponse($this->ruleRepository->responseFromTransaction);
        } else {
            $this->logErrorFromException($this->ruleRepository->exceptionFromTransaction);
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
    public function store(RuleRegisterRequest $request)
    {

        $requestValidated = $request->validated();

        $this->ruleRepository->create($requestValidated);

        if ($this->ruleRepository->transactionIsSuccessfully) {
            $ruleCreated =
                $this->ruleRepository->getResourceModel($this->ruleRepository->responseFromTransaction);

            $this->setSuccessResponse($ruleCreated, 'rule', Response::HTTP_CREATED);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.created.error',
                ['resource' => $this->ruleRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function show(Rule $rule)
    {
        return $this->ruleRepository->getResourceModel($rule);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function edit(Rule $rule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function update(RuleUpdateRequest $request, Rule $rule)
    {
        $this->rule = $rule;

        $requestValidated = $request->validated();

        $this->ruleRepository->update($requestValidated, $this->rule);

        if ($this->ruleRepository->transactionIsSuccessfully) {

            $ruleUpdated =
                $this->ruleRepository->getResourceModel($this->rule);

            $this->setSuccessResponse($ruleUpdated, 'rule', Response::HTTP_OK);
        } else {
            $this->setErrorResponse(__(
                'httpResponses.updated.error',
                ['resource' => $this->ruleRepository->resourceName]
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rule $rule)
    {
        $this->rule = $rule;


        $this->ruleRepository->delete($this->rule);

        if ($this->ruleRepository->transactionIsSuccessfully) {


            $this->setSuccessResponse(
                __(
                    'httpResponses.deleted.success',
                    ['resource' => $this->ruleRepository->resourceName]
                ),
                ApiController::KEY_SUCCESS_CONTENT,
                Response::HTTP_OK
            );
        } else {
            $this->setErrorResponse(__(
                'httpResponses.deleted.error',
                $this->ruleRepository->resourceName
            ), 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
