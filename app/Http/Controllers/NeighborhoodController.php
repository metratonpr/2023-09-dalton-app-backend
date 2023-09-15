<?php

namespace App\Http\Controllers;

use App\Models\Neighborhood;
use App\Http\Requests\StoreNeighborhoodRequest;
use App\Http\Requests\UpdateNeighborhoodRequest;

class NeighborhoodController extends Controller
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
     * @param  \App\Http\Requests\StoreNeighborhoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNeighborhoodRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Neighborhood  $neighborhood
     * @return \Illuminate\Http\Response
     */
    public function show(Neighborhood $neighborhood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Neighborhood  $neighborhood
     * @return \Illuminate\Http\Response
     */
    public function edit(Neighborhood $neighborhood)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNeighborhoodRequest  $request
     * @param  \App\Models\Neighborhood  $neighborhood
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNeighborhoodRequest $request, Neighborhood $neighborhood)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Neighborhood  $neighborhood
     * @return \Illuminate\Http\Response
     */
    public function destroy(Neighborhood $neighborhood)
    {
        //
    }
}
