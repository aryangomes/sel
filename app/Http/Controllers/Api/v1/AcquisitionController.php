<?php

namespace App\Http\Controllers;

use App\Models\Acquisition;
use Illuminate\Http\Request;

class AcquisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 200;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function show(Acquisition $acquisition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function edit(Acquisition $acquisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Acquisition $acquisition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Acquisition  $acquisition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Acquisition $acquisition)
    {
        //
    }
}
