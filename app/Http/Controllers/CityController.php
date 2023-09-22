<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::paginate(10);

        return response()->json(['data' => $cities]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCityRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $data = $request->validated();

        $city = City::create($data);

        return response()->json($city, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['error' => 'Cidade não encontrada.'], 404);
        }

        return response()->json(['data' => $city]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCityRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, $id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['error' => 'Cidade não encontrada.'], 404);
        }

        $data = $request->validated();

        $city->update($data);

        return response()->json(['data' => $city]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json(['error' => 'Cidade não encontrada.'], 404);
        }

        // Verificar se há códigos postais associados antes de excluir
        if ($city->zipcodes->count() > 0) {
            return response()->json(['error' => 'Esta cidade possui códigos postais associados e não pode ser excluída.'], 400);
        }

        $city->delete();

        return response()->json(['message' => 'Cidade deletada com sucesso.'], 200);
    }
}
