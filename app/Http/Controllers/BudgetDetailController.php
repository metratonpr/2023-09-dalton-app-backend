<?php

namespace App\Http\Controllers;

use App\Models\BudgetDetail;
use Illuminate\Http\Request;
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
        $budgetDetails = BudgetDetail::all();

        return response()->json(['data' => $budgetDetails]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBudgetDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBudgetDetailRequest $request)
    {
        $data = $request->validated();

        $budgetDetail = BudgetDetail::create($data);

        return response()->json($budgetDetail, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budgetDetail = BudgetDetail::find($id);

        if (!$budgetDetail) {
            return response()->json(['error' => 'Detalhe do Orçamento não encontrado.'], 404);
        }

        return response()->json(['data' => $budgetDetail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBudgetDetailRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBudgetDetailRequest $request, $id)
    {
        $budgetDetail = BudgetDetail::find($id);

        if (!$budgetDetail) {
            return response()->json(['error' => 'Detalhe do Orçamento não encontrado.'], 404);
        }

        $data = $request->validated();

        $budgetDetail->update($data);

        return response()->json(['data' => $budgetDetail]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $budgetDetail = BudgetDetail::find($id);

        if (!$budgetDetail) {
            return response()->json(['error' => 'Detalhe do Orçamento não encontrado.'], 404);
        }

        $budgetDetail->delete();

        return response()->json(['message' => 'Detalhe do Orçamento deletado com sucesso.'], 200);
    }
}
