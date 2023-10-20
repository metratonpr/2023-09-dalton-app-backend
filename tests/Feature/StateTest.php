<?php

namespace Tests\Feature;

use App\Models\City;
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
     * Deve listar estados com sucesso.
     */
    public function test_listar_estados_com_sucesso()
    {
        // Cria 10 estados usando a fábrica
        State::factory()->count(10)->create();

        // Faz uma requisição GET para a rota de listagem de estados
        $response = $this->getJson('/api/states');

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica se a resposta possui 10 itens no campo 'data'
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at']
                ]
            ]);
    }

    /**
     * Deve criar um estado com sucesso.
     */
    public function test_criar_estado_com_sucesso()
    {
        // Cria um novo estado usando a fábrica e transforma em um array
        $newData = State::factory()->make()->toArray();

        // Faz uma requisição POST para a rota de criação de estado
        $response = $this->postJson('/api/states', $newData);

        // Verifica se a resposta tem o status HTTP 201 (Created)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao criar um estado vazio.
     */
    public function test_falhar_criar_estado_vazio()
    {
        // Faz uma requisição POST para a rota de criação de estado com dados vazios
        $response = $this->postJson('/api/states', []);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve falhar ao criar um estado com o mesmo nome.
     */
    public function test_falhar_criar_estado_mesmo_nome()
    {
        // Cria um estado usando a fábrica
        $existingState = State::factory()->create();

        // Cria um novo estado usando a fábrica e define o mesmo nome do estado existente
        $newData = State::factory()->make(['name' => $existingState->name])->toArray();

        // Faz uma requisição POST para a rota de criação de estado com nome duplicado
        $response = $this->postJson('/api/states', $newData);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve exibir um estado com sucesso.
     */
    public function test_exibir_estado_com_sucesso()
    {
        // Cria um estado usando a fábrica
        $state = State::factory()->create();

        // Faz uma requisição GET para a rota de exibição do estado
        $response = $this->getJson('/api/states/' . $state->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        // Verifica se o nome do estado na resposta corresponde ao nome do estado criado
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson(['name' => $state->name]);
    }

    /**
     * Deve falhar ao exibir um estado inexistente.
     */
    public function test_falhar_exibir_estado_inexistente()
    {
        // Faz uma requisição GET para a rota de exibição de um estado inexistente
        $response = $this->getJson('/api/states/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Estado não encontrado.']);
    }

    /**
     * Deve atualizar um estado com sucesso.
     */
    public function test_atualizar_estado_com_sucesso()
    {
        // Cria um estado usando a fábrica
        $state = State::factory()->create();

        // Cria dados atualizados usando a fábrica
        $updatedData = State::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização do estado
        $response = $this->putJson('/api/states/' . $state->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao atualizar um estado inexistente.
     */
    public function test_falhar_atualizar_estado_inexistente()
    {
        // Cria dados atualizados usando a fábrica
        $updatedData = State::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização de um estado inexistente
        $response = $this->putJson('/api/states/9999999', $updatedData);

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Estado não encontrado.']);
    }

    /**
     * Deve falhar ao excluir um estado com cidades associadas.
     */
    public function test_falhar_excluir_estado_com_cidades_associadas()
    {
        // Cria um estado usando a fábrica
        $state = State::factory()->create();
        $city = City::factory()->make()->toArray();
        //Teste nova funcionalidade

        // Simula a existência de cidades associadas
        $state->cities()->create($city);

        // Faz uma requisição DELETE para a rota de exclusão do estado
        $response = $this->deleteJson('/api/states/' . $state->id);

        // Verifica se a resposta tem o status HTTP 400 (Bad Request)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(400)
            ->assertJson(['error' => 'Este estado possui cidades associadas e não pode ser excluído.']);
    }

    /**
     * Deve excluir um estado com sucesso.
     */
    public function test_excluir_estado_com_sucesso()
    {
        // Cria um estado usando a fábrica
        $state = State::factory()->create();

        // Faz uma requisição DELETE para a rota de exclusão do estado
        $response = $this->deleteJson('/api/states/' . $state->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a mensagem de sucesso retornada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Estado deletado com sucesso.']);
    }

    /**
     * Deve falhar ao excluir um estado inexistente.
     */
    public function test_falhar_excluir_estado_inexistente()
    {
        // Faz uma requisição DELETE para a rota de exclusão de um estado inexistente
        $response = $this->deleteJson('/api/states/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Estado não encontrado.']);
    }

    /**
     * Deve permitir atualizar o mesmo cadastro com o mesmo nome.
     */
    public function test_permitir_atualizar_estado_com_mesmo_nome_mesmo_id()
    {
        // Cria um estado usando a fábrica
        $state = State::factory()->create();

        // Cria dados atualizados com o mesmo nome e o mesmo ID
        $updatedData = $state->toArray();

        // Tenta atualizar o estado com os mesmos dados
        $response = $this->putJson('/api/states/' . $state->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }
}
