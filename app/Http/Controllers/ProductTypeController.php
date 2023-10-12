<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use App\Http\Requests\StoreProductTypeRequest;
use App\Http\Requests\UpdateProductTypeRequest;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTypes = ProductType::all();

        return response()->json(['data' => $productTypes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductTypeRequest $request)
    {
        $data = $request->validated();

        $productType = ProductType::create($data);

        return response()->json($productType, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $productType = ProductType::find($id);

        if (!$productType) {
            return response()->json(['error' => 'Tipo de Produto não encontrado.'], 404);
        }

        return response()->json($productType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductTypeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductTypeRequest $request, $id)
    {
        $productType = ProductType::find($id);

        if (!$productType) {
            return response()->json(['error' => 'Tipo de Produto não encontrado.'], 404);
        }

        $data = $request->validated();

        $productType->update($data);

        return response()->json($productType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productType = ProductType::find($id);

        if (!$productType) {
            return response()->json(['error' => 'Tipo de Produto não encontrado.'], 404);
        }

        // Verificar se há produtos associados antes de excluir
        if ($productType->products->count() > 0) {
            return response()->json(['error' =>  'Este Tipo de Produto possui produtos associados e não pode ser excluído.'], 400);
        }

        $productType->delete();

        return response()->json(['message' => 'Tipo de Produto deletado com sucesso.'], 200);
    }
}
