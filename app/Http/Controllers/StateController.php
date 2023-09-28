<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $states = State::all();

        return response()->json(['data' => $states]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStateRequest $request)
    {
        $data = $request->validated();

        $state = State::create($data);

        return response()->json($state, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $state = State::find($id);

        if (!$state) {
            return response()->json(['error' => 
            'Estado não encontrado.'],   404);
        }

        return response()->json($state,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStateRequest $request, $id)
    {
        $state = State::find($id);
        if (!$state) {
            return response()->json(['error' => 
            'Estado não encontrado.'], 404);
        }
        $data = $request->validated();
        $state->update($data);
        return response()->json($state);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::find($id);

        if (!$state) {
            return response()->json(['error' => 
            'Estado não encontrado.'], 404);
        }

        // Verificar se há cidades associadas antes de excluir
        if ($state->cities->count() > 0) {
            return response()->json(['error' => 
            'Este estado possui cidades associadas e não pode ser excluído.'], 400);
        }

        $state->delete();

        return response()->json(['message' => 
        'Estado deletado com sucesso.'], 200);
    }
}
