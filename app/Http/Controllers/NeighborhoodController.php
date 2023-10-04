<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNeighborhoodRequest;
use App\Http\Requests\UpdateNeighborhoodRequest;
use App\Models\Neighborhood;


class NeighborhoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $neighborhoods = Neighborhood::all();
        return response()->json(['data' => $neighborhoods]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNeighborhoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNeighborhoodRequest $request)
    {
        $data = $request->validated();

        $neighborhood = Neighborhood::create($data);

        return response()->json($neighborhood, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $neighborhood = Neighborhood::find($id);

        if (!$neighborhood) {
            return response()->json(['error' => 'Bairro não encontrado.'], 404);
        }

        return response()->json($neighborhood);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNeighborhoodRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNeighborhoodRequest $request, $id)
    {
        $neighborhood = Neighborhood::find($id);

        if (!$neighborhood) {
            return response()->json(['error' => 'Bairro não encontrado.'], 404);
        }

        $data = $request->validated();

        $neighborhood->update($data);

        return response()->json(['data' => $neighborhood]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $neighborhood = Neighborhood::find($id);

        if (!$neighborhood) {
            return response()->json(['error' => 'Bairro não encontrado.'], 404);
        }

        // Verificar se há códigos postais associados antes de excluir
        if ($neighborhood->zipcodes->count() > 0) {
            return response()->json(['error' => 'Este bairro possui códigos postais associados e não pode ser excluído.'], 400);
        }

        $neighborhood->delete();

        return response()->json(['message' => 'Bairro deletado com sucesso.'], 200);
    }
}
