<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Models\Lender;
use App\Http\Requests\Lender\LenderRegisterRequest;
use App\Http\Requests\Lender\LenderUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class LenderController extends Controller
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
    public function store(LenderRegisterRequest $request)
    {
        $requestValidated = $request->validated();

        $lenderWasCreated = false;

        // $this->authorize('create', new Lender());

        try {
            DB::beginTransaction();

            $lenderCreated = Lender::create($requestValidated);

            $lenderWasCreated = isset($lenderCreated);
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        if ($lenderWasCreated) {
            DB::commit();

            $this->setSuccessResponse($lenderCreated, 'lender',  Response::HTTP_CREATED);
        } else {
            DB::rollBack();
            $this->setErrorResponse();
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Models\Lender  $lender
     * @return \Illuminate\Http\Response
     */
    public function show(Lender $lender)
    {
        // $this->authorize('view', $lender);
        return $lender;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Models\Lender  $lender
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
     * @param  \App\Http\Models\Lender  $lender
     * @return \Illuminate\Http\Response
     */
    public function update(LenderUpdateRequest $request, Lender $lender)
    {
        $requestValidated = $request->validated();

        $lenderWasUpdated = false;

        // $this->authorize('update', $lender);

        try {
            DB::beginTransaction();

            $lenderWasUpdated = $lender->update($requestValidated);
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        if ($lenderWasUpdated) {
            DB::commit();

            $this->setSuccessResponse($lender, 'lender', 200);
        } else {
            DB::rollBack();
            $this->setErrorResponse();
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Models\Lender  $lender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lender $lender)
    {
        $lenderWasDeleted = false;

        // $this->authorize('delete', $lender);

        try {
            DB::beginTransaction();

            $lenderWasDeleted = $lender->delete();
        } catch (\Exception $exception) {
            $this->logErrorFromException($exception);
        }


        if ($lenderWasDeleted) {
            DB::commit();
            $this->setSuccessResponse('Lender deleted successfully', 'success', Response::HTTP_OK);
        } else {
            DB::rollBack();
            $this->setErrorResponse('Lender deleted failed', 'errors', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->responseWithJson();
    }
}
