<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = Address::paginate(10);

        return response()->json(['data' => $addresses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddressRequest $request)
    {
        $data = $request->validated();

        $address = Address::create($data);

        return response()->json($address, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['error' => 'Endereço não encontrado.'], 404);
        }

        return response()->json(['data' => $address]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAddressRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddressRequest $request, $id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['error' => 'Endereço não encontrado.'], 404);
        }

        $data = $request->validated();

        $address->update($data);

        return response()->json(['data' => $address]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return response()->json(['error' => 'Endereço não encontrado.'], 404);
        }

        // Verificar se há orçamentos ou lojas associadas antes de excluir
        if ($address->budgets->count() > 0 || $address->stores->count() > 0) {
            return response()->json(['error' => 'Este endereço possui orçamentos ou lojas associadas e não pode ser excluído.'], 400);
        }

        $address->delete();

        return response()->json(['message' => 'Endereço deletado com sucesso.'], 200);
    }
}
