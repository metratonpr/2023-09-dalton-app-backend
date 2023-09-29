<?php

namespace Tests\Feature;

use App\Models\Country;
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
    public function test_salvar_pesquisar_estados_banco_dados()
    {
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
    public function test_criar_novo_estado_com_sucesso()
    {
        //Criar um estado sem salvar
        $newData = StateFactory::new()->make()->getAttributes();
        //Processar
        $response = $this->postJson('/api/states', $newData);

        //Deu certo a solicitação = Status 201
        $response->assertStatus(201);

        // Verifique se a estrutura dos dados retornados está correta
        $response->assertJsonStructure([
            'id',
            'name',
            'country_id',
            'created_at',
            'updated_at',
        ]);
        //verificar o conteudo
        $response->assertJson([
            'name' => $newData['name'],
            'country_id' => $newData['country_id'],
        ]);
    }

    /**
     * Tentar criar um registro e falhar
     * @return void
     */
    public function test_criacao_estado_com_falha()
    {
        $newData = [
            'name' => '',
            'country_id' => 99999,
        ];

        // Faça uma requisição POST para a rota de criação
        $response = $this->postJson('/api/states', $newData);

        // Verifique se a resposta tem status 422
        $response->assertStatus(422);

        // Verifique se há erros de validação 
        $response->assertJsonValidationErrors(['name', 'country_id']);
    }

    /**
     * Tentar salvar com nome duplicado e falhar
     * @return void
     */
    public function test_tentar_salvar_estado_com_mesmo_nome_falhar()
    {

        //Criar um teste
        $data  = State::factory()->create();
        $newData = [
            'name' => $data->name,
            'country_id' => $data->country_id
        ];

        // Faça uma requisição POST para a rota de criação
        $response = $this->postJson('/api/states', $newData);

        // Verifique se a resposta tem status 422
        $response->assertStatus(422);

        // Verifique se há erros de validação 
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Criar um estado e pesquisar pelo seu id
     * @return void
     */
    public function test_criar_estado_pesquisar_pelo_id()
    {

        //Criar estado
        $state = State::factory()->create();

        //Processar
        $response = $this->getJson('/api/states/' . $state->id);

        //Deu certo a solicitação = Status 200
        $response->assertStatus(200);
        // Verifique se a estrutura dos dados retornados está correta
        $response->assertJsonStructure([
            'id', 'name', 'country_id','created_at','updated_at',
        ]);
        //verificar o conteudo
        $response->assertJson([
            'name' => $state->name,
            'country_id' => $state->country_id,
        ]);
    }

    /**
     * Testa a exibição de um estado inexistente.
     *
     * @return void
     */
    public function test_exibicao_estado_inexistente()
    {
        // Faça uma requisição GET para a rota
        $response = $this->getJson('/api/states/999999');

        // Verifique se a resposta tem status 404 (Not Found)
        $response->assertStatus(404);

        // Verifique se a mensagem de erro é retornada
        $response->assertJson([
            'error' => 'Estado não encontrado.',
        ]);
    }

    /**
     * Testa atualização com sucesso!
     * @return void
     */
    public function test_criar_atualizar_state_com_sucesso(){
        //Criar estado
        $state = State::factory()->create();

        //Dados a serem atualizados
        $newData = [
            'name' => $this->faker()->word(),
            'country_id' => $state->country_id,
        ];

        $response = $this->putJson('/states/'.$state->id,$newData);

        //testar argumentos
        $response
        ->assertStatus(200)
        ->assertJson([
            'id' => $state->id,
            'name' => $newData['name'],
            'country_id' => $newData['country_id'],
        ]);
    }

    /**
     * Testar salvar com o mesmo nome e id
     * @return void
     */
    public function test_criar_atualizar_state_mesmo_nome_com_sucesso(){
        //Criar estado
        $state = State::factory()->create();
        //Criar novo
        $pais = Country::factory()->create();

        //Dados a serem atualizados
        $newData = [
            'name' => $state->name,
            'country_id' => $pais->id,
        ];

        $response = $this->putJson('/states/'.$state->id,$newData);

        //testar argumentos
        $response
        ->assertStatus(200)
        ->assertJson([
            'id' => $state->id,
            'name' => $state->name,
            'country_id' => $pais->id,
        ]);
    }
}
