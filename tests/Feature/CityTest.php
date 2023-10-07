<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\ZipCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Deve listar cidades com sucesso.
     */
    public function test_listar_cidades_com_sucesso()
    {
        // Cria 10 cidades usando a fábrica
        City::factory()->count(10)->create();

        // Faz uma requisição GET para a rota de listagem de cidades
        $response = $this->getJson('/api/cities');

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
     * Deve criar uma cidade com sucesso.
     */
    public function test_criar_cidade_com_sucesso()
    {
        // Cria uma nova cidade usando a fábrica e transforma em um array
        $newData = City::factory()->make()->toArray();

        // Faz uma requisição POST para a rota de criação de cidade
        $response = $this->postJson('/api/cities', $newData);

        // Verifica se a resposta tem o status HTTP 201 (Created)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao criar uma cidade vazia.
     */
    public function test_falhar_criar_cidade_vazia()
    {
        // Faz uma requisição POST para a rota de criação de cidade com dados vazios
        $response = $this->postJson('/api/cities', []);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve falhar ao criar uma cidade com o mesmo nome.
     */
    public function test_falhar_criar_cidade_mesmo_nome()
    {
        // Cria uma cidade usando a fábrica
        $existingCity = City::factory()->create();

        // Cria uma nova cidade usando a fábrica e define o mesmo nome da cidade existente
        $newData = City::factory()->make(['name' => $existingCity->name])->toArray();

        // Faz uma requisição POST para a rota de criação de cidade com nome duplicado
        $response = $this->postJson('/api/cities', $newData);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve exibir uma cidade com sucesso.
     */
    public function test_exibir_cidade_com_sucesso()
    {
        // Cria uma cidade usando a fábrica
        $city = City::factory()->create();

        // Faz uma requisição GET para a rota de exibição da cidade
        $response = $this->getJson('/api/cities/' . $city->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        // Verifica se o nome da cidade na resposta corresponde ao nome da cidade criada
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson(['name' => $city->name]);
    }

    /**
     * Deve falhar ao exibir uma cidade inexistente.
     */
    public function test_falhar_exibir_cidade_inexistente()
    {
        // Faz uma requisição GET para a rota de exibição de uma cidade inexistente
        $response = $this->getJson('/api/cities/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Cidade não encontrada.']);
    }

    /**
     * Deve atualizar uma cidade com sucesso.
     */
    public function test_atualizar_cidade_com_sucesso()
    {
        // Cria uma cidade usando a fábrica
        $city = City::factory()->create();

        // Cria dados atualizados usando a fábrica
        $updatedData = City::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização da cidade
        $response = $this->putJson('/api/cities/' . $city->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao atualizar uma cidade inexistente.
     */
    public function test_falhar_atualizar_cidade_inexistente()
    {
        // Cria dados atualizados usando a fábrica
        $updatedData = City::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização de uma cidade inexistente
        $response = $this->putJson('/api/cities/9999999', $updatedData);

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Cidade não encontrada.']);
    }

    /**
     * Deve falhar ao excluir uma cidade com códigos postais associados.
     */
    public function test_falhar_excluir_cidade_com_codigos_postais_associados()
    {
        // Cria uma cidade usando a fábrica
        $city = City::factory()->create();
        $zipcode = ZipCode::factory()->make()->toArray();

        // Simula a existência de códigos postais associados
        $city->zipcodes()->create($zipcode);

        // Faz uma requisição DELETE para a rota de exclusão da cidade
        $response = $this->deleteJson('/api/cities/' . $city->id);

        // Verifica se a resposta tem o status HTTP 400 (Bad Request)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(400)
            ->assertJson(['error' => 'Esta cidade possui códigos postais associados e não pode ser excluída.']);
    }

    /**
     * Deve excluir uma cidade com sucesso.
     */
    public function test_excluir_cidade_com_sucesso()
    {
        // Cria uma cidade usando a fábrica
        $city = City::factory()->create();

        // Faz uma requisição DELETE para a rota de exclusão da cidade
        $response = $this->deleteJson('/api/cities/' . $city->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a mensagem de sucesso retornada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Cidade deletada com sucesso.']);
    }

    /**
     * Deve falhar ao excluir uma cidade inexistente.
     */
    public function test_falhar_excluir_cidade_inexistente()
    {
        // Faz uma requisição DELETE para a rota de exclusão de uma cidade inexistente
        $response = $this->deleteJson('/api/cities/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Cidade não encontrada.']);
    }

    /**
     * Deve permitir atualizar o mesmo cadastro com o mesmo nome.
     */
    public function test_permitir_atualizar_cidade_com_mesmo_nome_mesmo_id()
    {
        // Cria uma cidade usando a fábrica
        $city = City::factory()->create();

        // Cria dados atualizados com o mesmo nome e o mesmo ID
        $updatedData = $city->toArray();

        // Tenta atualizar a cidade com os mesmos dados
        $response = $this->putJson('/api/cities/' . $city->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }
}
