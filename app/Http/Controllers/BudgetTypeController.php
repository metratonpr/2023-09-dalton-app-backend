<?php

namespace App\Http\Controllers;

use App\Models\BudgetType;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBudgetTypeRequest;
use App\Http\Requests\UpdateBudgetTypeRequest;

class BudgetTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $budgetTypes = BudgetType::all();

        return response()->json(['data' => $budgetTypes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBudgetTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBudgetTypeRequest $request)
    {
        $data = $request->validated();

        $budgetType = BudgetType::create($data);

        return response()->json($budgetType, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $budgetType = BudgetType::find($id);

        if (!$budgetType) {
            return response()->json(['error' => 'Tipo de Orçamento não encontrado.'], 404);
        }

        return response()->json($budgetType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBudgetTypeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBudgetTypeRequest $request, $id)
    {
        $budgetType = BudgetType::find($id);

        if (!$budgetType) {
            return response()->json(['error' => 'Tipo de Orçamento não encontrado.'], 404);
        }

        $data = $request->validated();

        $budgetType->update($data);

        return response()->json($budgetType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $budgetType = BudgetType::find($id);

        if (!$budgetType) {
            return response()->json(['error' => 'Tipo de Orçamento não encontrado.'], 404);
        }

        // Verificar se há orçamentos ou lojas associadas antes de excluir
        if ($budgetType->budgets->count() > 0) {
            return response()->json(['error' => 'Este Tipo de Orçamento possui orçamentos ou lojas associadas e não pode ser excluído.'], 400);
        }

        $budgetType->delete();

        return response()->json(['message' => 'Tipo de Orçamento deletado com sucesso.'], 200);
    }
}
