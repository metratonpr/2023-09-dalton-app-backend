<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;


class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::all();

        return response()->json(['data' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCountryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $request)
    {
        $data = $request->validated();

        $country = Country::create($data);

        return response()->json($country, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json(['error' => 'País não encontrado.'], 404);
        }

        return response()->json(['data' => $country]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCountryRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $request, $id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json(['error' => 'País não encontrado.'], 404);
        }

        $data = $request->validated();

        $country->update($data);

        return response()->json(['data' => $country]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::find($id);

        if (!$country) {
            return response()->json(['error' => 'País não encontrado.'], 404);
        }

        // Verificar se há estados associados antes de excluir
        if ($country->states->count() > 0) {
            return response()->json(['error' => 'Este país possui estados associados e não pode ser excluído.'], 400);
        }

        $country->delete();

        return response()->json(['message' => 'País deletado com sucesso.'], 200);
    }
}
