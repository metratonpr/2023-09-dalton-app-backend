<?php

namespace App\Http\Controllers;

use App\Models\Store;

use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::all();

        return response()->json(['data' => $stores]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStoreRequest $request)
    {
        $data = $request->validated();

        $store = Store::create($data);

        return response()->json($store, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = Store::find($id);

        if (!$store) {
            return response()->json(['error' => 'Loja não encontrada.'], 404);
        }

        return response()->json($store);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStoreRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStoreRequest $request, $id)
    {
        $store = Store::find($id);

        if (!$store) {
            return response()->json(['error' => 'Loja não encontrada.'], 404);
        }

        $data = $request->validated();

        $store->update($data);

        return response()->json($store);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::find($id);

        if (!$store) {
            return response()->json(['error' => 'Loja não encontrada.'], 404);
        }

        // Verificar se há listas de preços associadas antes de excluir
        if ($store->priceList->count() > 0) {
            return response()->json(['error' => 'Esta loja possui listas de preços associadas e não pode ser excluída.'], 400);
        }

        $store->delete();

        return response()->json(['message' => 'Loja deletada com sucesso.'], 200);
    }
}
