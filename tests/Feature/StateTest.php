<?php

namespace Tests\Feature;

use App\Models\State;
use Database\Factories\StateFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StateTest extends TestCase
{
    
    use RefreshDatabase, WithFaker;

    /**
     * Testar consulta no banco de dados.
     * Deve retornar 10 itens.
     * @return void
     */
    public function test_salvar_pesquisar_estados_banco_dados(){
        //Preparar os dados ou parametros
        State::factory()->count(10)->create();
        //Processar
        $response = $this->getJson('/api/states');
        //Avaliar

        //Deu certo a solicitação = Status 200
        $response->assertStatus(200);

        //Quantidade de itens
        $response->assertJsonCount(10, 'data');

        // Verifique a estrutura dos dados retornados
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'country_id',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    /**
     * Criar um registro com sucesso
     * @return void
     */
    public function test_criar_novo_estado_com_sucesso(){
        //Criar um estado sem salvar
        $newData = StateFactory::new()->make()->getAttributes();
        dd($newData);

    }

}
