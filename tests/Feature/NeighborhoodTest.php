<?php

namespace Tests\Feature;

use App\Models\Neighborhood;
use App\Models\ZipCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NeighborhoodTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Deve listar bairros com sucesso.
     */
    public function test_listar_bairros_com_sucesso()
    {
        // Cria 10 bairros usando a fábrica
        Neighborhood::factory()->count(10)->create();

        // Faz uma requisição GET para a rota de listagem de bairros
        $response = $this->getJson('/api/neighborhoods');

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
     * Deve criar um bairro com sucesso.
     */
    public function test_criar_bairro_com_sucesso()
    {
        // Cria um novo bairro usando a fábrica e transforma em um array
        $newData = Neighborhood::factory()->make()->toArray();

        // Faz uma requisição POST para a rota de criação de bairro
        $response = $this->postJson('/api/neighborhoods', $newData);

        // Verifica se a resposta tem o status HTTP 201 (Created)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao criar um bairro vazio.
     */
    public function test_falhar_criar_bairro_vazio()
    {
        // Faz uma requisição POST para a rota de criação de bairro com dados vazios
        $response = $this->postJson('/api/neighborhoods', []);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve falhar ao criar um bairro com o mesmo nome.
     */
    public function test_falhar_criar_bairro_mesmo_nome()
    {
        // Cria um bairro usando a fábrica
        $existingNeighborhood = Neighborhood::factory()->create();

        // Cria um novo bairro usando a fábrica e define o mesmo nome do bairro existente
        $newData = Neighborhood::factory()->make(['name' => $existingNeighborhood->name])->toArray();

        // Faz uma requisição POST para a rota de criação de bairro com nome duplicado
        $response = $this->postJson('/api/neighborhoods', $newData);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve exibir um bairro com sucesso.
     */
    public function test_exibir_bairro_com_sucesso()
    {
        // Cria um bairro usando a fábrica
        $neighborhood = Neighborhood::factory()->create();

        // Faz uma requisição GET para a rota de exibição do bairro
        $response = $this->getJson('/api/neighborhoods/' . $neighborhood->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        // Verifica se o nome do bairro na resposta corresponde ao nome do bairro criado
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson(['name' => $neighborhood->name]);
    }

    /**
     * Deve falhar ao exibir um bairro inexistente.
     */
    public function test_falhar_exibir_bairro_inexistente()
    {
        // Faz uma requisição GET para a rota de exibição de um bairro inexistente
        $response = $this->getJson('/api/neighborhoods/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Bairro não encontrado.']);
    }

    /**
     * Deve atualizar um bairro com sucesso.
     */
    public function test_atualizar_bairro_com_sucesso()
    {
        // Cria um bairro usando a fábrica
        $neighborhood = Neighborhood::factory()->create();

        // Cria dados atualizados usando a fábrica
        $updatedData = Neighborhood::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização do bairro
        $response = $this->putJson('/api/neighborhoods/' . $neighborhood->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao atualizar um bairro inexistente.
     */
    public function test_falhar_atualizar_bairro_inexistente()
    {
        // Cria dados atualizados usando a fábrica
        $updatedData = Neighborhood::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização de um bairro inexistente
        $response = $this->putJson('/api/neighborhoods/9999999', $updatedData);

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Bairro não encontrado.']);
    }

    /**
     * Deve falhar ao excluir um bairro com relacionamentos (códigos postais associados).
     */
    public function test_falhar_excluir_bairro_com_relacionamentos()
    {
        // Cria um bairro usando a fábrica
        $neighborhood = Neighborhood::factory()->create();
        $zipcode = ZipCode::factory()->make()->toArray();

        // Simula a existência de códigos postais associados
        $neighborhood->zipcodes()->create($zipcode);

        // Faz uma requisição DELETE para a rota de exclusão do bairro
        $response = $this->deleteJson('/api/neighborhoods/' . $neighborhood->id);

        // Verifica se a resposta tem o status HTTP 400 (Bad Request)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(400)
            ->assertJson(['error' => 'Este bairro possui códigos postais associados e não pode ser excluído.']);
    }

    /**
     * Deve excluir um bairro com sucesso.
     */
    public function test_excluir_bairro_com_sucesso()
    {
        // Cria um bairro usando a fábrica
        $neighborhood = Neighborhood::factory()->create();

        // Faz uma requisição DELETE para a rota de exclusão do bairro
        $response = $this->deleteJson('/api/neighborhoods/' . $neighborhood->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a mensagem de sucesso retornada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Bairro deletado com sucesso.']);
    }

    public function test_falhar_excluir_bairro_inexistente()
    {
        // Faz uma requisição DELETE para a rota de exclusão de um bairro inexistente
        $response = $this->deleteJson('/api/neighborhoods/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Bairro não encontrado.']);
    }

    /**
     * Deve permitir atualizar o mesmo cadastro com o mesmo nome.
     */
    public function test_permitir_atualizar_bairro_com_mesmo_nome_mesmo_id()
    {
        // Cria um bairro usando a fábrica
        $neighborhood = Neighborhood::factory()->create();

        // Cria dados atualizados com o mesmo nome e o mesmo ID
        $updatedData = $neighborhood->toArray();

        // Tenta atualizar o bairro com os mesmos dados
        $response = $this->putJson('/api/neighborhoods/' . $neighborhood->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }
}
