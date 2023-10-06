<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $budgets = Budget::all();

        return response()->json(['data' => $budgets]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBudgetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBudgetRequest $request)
    {
        $data = $request->validated();

        $budget = Budget::create($data);

        return response()->json($budget, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json(['error' => 'Orçamento não encontrado.'], 404);
        }

        return response()->json(['data' => $budget]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBudgetRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBudgetRequest $request, $id)
    {
        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json(['error' => 'Orçamento não encontrado.'], 404);
        }

        $data = $request->validated();

        $budget->update($data);

        return response()->json(['data' => $budget]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json(['error' => 'Orçamento não encontrado.'], 404);
        }

        $budget->budgetDetails()->delete(); // Exclui detalhes de orçamento associados

        $budget->delete();

        return response()->json(['message' => 'Orçamento deletado com sucesso.'], 200);
    }
}
