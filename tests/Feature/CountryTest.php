<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Deve listar países com sucesso.
     */
    public function test_listar_paises_com_sucesso()
    {
        // Cria 10 países usando a fábrica
        Country::factory()->count(10)->create();

        // Faz uma requisição GET para a rota de listagem de países
        $response = $this->getJson('/api/countries');

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
     * Deve criar um país com sucesso.
     */
    public function test_criar_pais_com_sucesso()
    {
        // Cria um novo país usando a fábrica e transforma em um array
        $newData = Country::factory()->make()->toArray();

        // Faz uma requisição POST para a rota de criação de país
        $response = $this->postJson('/api/countries', $newData);

        // Verifica se a resposta tem o status HTTP 201 (Created)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao criar um país vazio.
     */
    public function test_falhar_criar_pais_vazio()
    {
        // Faz uma requisição POST para a rota de criação de país com dados vazios
        $response = $this->postJson('/api/countries', []);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve falhar ao criar um país com o mesmo nome.
     */
    public function test_falhar_criar_pais_mesmo_nome()
    {
        // Cria um país usando a fábrica
        $existingCountry = Country::factory()->create();

        // Cria um novo país usando a fábrica e define o mesmo nome do país existente
        $newData = Country::factory()->make(['name' => $existingCountry->name])->toArray();

        // Faz uma requisição POST para a rota de criação de país com nome duplicado
        $response = $this->postJson('/api/countries', $newData);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve exibir um país com sucesso.
     */
    public function test_exibir_pais_com_sucesso()
    {
        // Cria um país usando a fábrica
        $country = Country::factory()->create();

        // Faz uma requisição GET para a rota de exibição do país
        $response = $this->getJson('/api/countries/' . $country->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        // Verifica se o nome do país na resposta corresponde ao nome do país criado
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson(['name' => $country->name]);
    }

    /**
     * Deve falhar ao exibir um país inexistente.
     */
    public function test_falhar_exibir_pais_inexistente()
    {
        // Faz uma requisição GET para a rota de exibição de um país inexistente
        $response = $this->getJson('/api/countries/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'País não encontrado.']);
    }

    /**
     * Deve atualizar um país com sucesso.
     */
    public function test_atualizar_pais_com_sucesso()
    {
        // Cria um país usando a fábrica
        $country = Country::factory()->create();

        // Cria dados atualizados usando a fábrica
        $updatedData = Country::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização do país
        $response = $this->putJson('/api/countries/' . $country->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao atualizar um país inexistente.
     */
    public function test_falhar_atualizar_pais_inexistente()
    {
        // Cria dados atualizados usando a fábrica
        $updatedData = Country::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização de um país inexistente
        $response = $this->putJson('/api/countries/9999999', $updatedData);

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'País não encontrado.']);
    }

    /**
     * Deve falhar ao excluir um país com estados associados.
     */
    public function test_falhar_excluir_pais_com_estados_associados()
    {
        // Cria um país usando a fábrica
        $country = Country::factory()->create();
        $state = State::factory()->make()->toArray();

        // Simula a existência de estados associados
        $country->states()->create($state);

        // Faz uma requisição DELETE para a rota de exclusão do país
        $response = $this->deleteJson('/api/countries/' . $country->id);

        // Verifica se a resposta tem o status HTTP 400 (Bad Request)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(400)
            ->assertJson(['error' => 'Este país possui estados associados e não pode ser excluído.']);
    }

    /**
     * Deve excluir um país com sucesso.
     */
    public function test_excluir_pais_com_sucesso()
    {
        // Cria um país usando a fábrica
        $country = Country::factory()->create();

        // Faz uma requisição DELETE para a rota de exclusão do país
        $response = $this->deleteJson('/api/countries/' . $country->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a mensagem de sucesso retornada
        $response->assertStatus(200)
            ->assertJson(['message' => 'País deletado com sucesso.']);
    }

    /**
     * Deve falhar ao excluir um país inexistente.
     */
    public function test_falhar_excluir_pais_inexistente()
    {
        // Faz uma requisição DELETE para a rota de exclusão de um país inexistente
        $response = $this->deleteJson('/api/countries/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'País não encontrado.']);
    }

    /**
     * Deve permitir atualizar o mesmo cadastro com o mesmo nome.
     */
    public function test_permitir_atualizar_pais_com_mesmo_nome_mesmo_id()
    {
        // Cria um país usando a fábrica
        $country = Country::factory()->create();

        // Cria dados atualizados com o mesmo nome e o mesmo ID
        $updatedData = $country->toArray();

        // Tenta atualizar o país com os mesmos dados
        $response = $this->putJson('/api/countries/' . $country->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }
}
