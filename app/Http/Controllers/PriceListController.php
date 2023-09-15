<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use App\Http\Requests\StorePriceListRequest;
use App\Http\Requests\UpdatePriceListRequest;

class PriceListController extends Controller
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
     * @param  \App\Http\Requests\StorePriceListRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePriceListRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function show(PriceList $priceList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceList $priceList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePriceListRequest  $request
     * @param  \App\Models\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceListRequest $request, PriceList $priceList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PriceList  $priceList
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceList $priceList)
    {
        //
    }
}
