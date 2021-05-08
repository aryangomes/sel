<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AcquisitionType;
use App\Repositories\Interfaces\AcquisitionTypeRepositoryInterface;
use App\Repositories\AcquisitionTypeRepository;
use Illuminate\Http\Request;

class AcquisitionTypeController extends Controller
{

    private $acquisitionTypeRepository;

    public function __construct(AcquisitionTypeRepository $acquisitionTypeRepository)
    {
        $this->acquisitionTypeRepository = $acquisitionTypeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $acquisitionTypes = $this->acquisitionTypeRepository->findAll();
        return $acquisitionTypes;
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
    public function store(Request $request)
    {
        $acquisitionTypes = $this->acquisitionTypeRepository->create($request->all());
        return $acquisitionTypes;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function show(AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function edit(AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcquisitionType $acquisitionType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AcquisitionType  $acquisitionType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcquisitionType $acquisitionType)
    {
        //
    }
}
