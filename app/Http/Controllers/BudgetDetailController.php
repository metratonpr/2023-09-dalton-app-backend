<?php

namespace App\Http\Controllers;

use App\Models\BudgetDetail;
use App\Http\Requests\StoreBudgetDetailRequest;
use App\Http\Requests\UpdateBudgetDetailRequest;

class BudgetDetailController extends Controller
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
     * @param  \App\Http\Requests\StoreBudgetDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBudgetDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function show(BudgetDetail $budgetDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(BudgetDetail $budgetDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBudgetDetailRequest  $request
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBudgetDetailRequest $request, BudgetDetail $budgetDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BudgetDetail  $budgetDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetDetail $budgetDetail)
    {
        //
    }
}
