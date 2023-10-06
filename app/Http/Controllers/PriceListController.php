<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use Illuminate\Http\Request;
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
        $priceLists = PriceList::all();

        return response()->json(['data' => $priceLists]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePriceListRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePriceListRequest $request)
    {
        $data = $request->validated();

        $priceList = PriceList::create($data);

        return response()->json($priceList, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $priceList = PriceList::find($id);

        if (!$priceList) {
            return response()->json(['error' => 'Lista de Preços não encontrada.'], 404);
        }

        return response()->json($priceList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePriceListRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceListRequest $request, $id)
    {
        $priceList = PriceList::find($id);

        if (!$priceList) {
            return response()->json(['error' => 'Lista de Preços não encontrada.'], 404);
        }

        $data = $request->validated();

        $priceList->update($data);

        return response()->json($priceList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $priceList = PriceList::find($id);

        if (!$priceList) {
            return response()->json(['error' => 'Lista de Preços não encontrada.'], 404);
        }

        // Verificar se há detalhes de orçamento associados antes de excluir
        if ($priceList->budgetDetails->count() > 0) {
            return response()->json(['error' => 'Esta Lista de Preços possui detalhes de orçamento associados e não pode ser excluída.'], 400);
        }

        $priceList->delete();

        return response()->json(['message' => 'Lista de Preços deletada com sucesso.'], 200);
    }
}
