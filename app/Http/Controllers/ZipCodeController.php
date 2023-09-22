<?php

namespace App\Http\Controllers;

use App\Models\ZipCode;
use Illuminate\Http\Request;


class ZipCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zipCodes = ZipCode::paginate(10);

        return response()->json(['data' => $zipCodes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreZipCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreZ $request)
    {
        $data = $request->validated();

        $zipCode = ZipCode::create($data);

        return response()->json($zipCode, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zipCode = ZipCode::find($id);

        if (!$zipCode) {
            return response()->json(['error' => 'Código Postal não encontrado.'], 404);
        }

        return response()->json(['data' => $zipCode]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateZipCodeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateZipcodeRequest $request, $id)
    {
        $zipCode = ZipCode::find($id);

        if (!$zipCode) {
            return response()->json(['error' => 'Código Postal não encontrado.'], 404);
        }

        $data = $request->validated();

        $zipCode->update($data);

        return response()->json(['data' => $zipCode]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zipCode = ZipCode::find($id);

        if (!$zipCode) {
            return response()->json(['error' => 'Código Postal não encontrado.'], 404);
        }

        // Verificar se há endereços associados antes de excluir
        if ($zipCode->addresses->count() > 0) {
            return response()->json(['error' => 'Este Código Postal possui endereços associados e não pode ser excluído.'], 400);
        }

        $zipCode->delete();

        return response()->json(['message' => 'Código Postal deletado com sucesso.'], 200);
    }
}
