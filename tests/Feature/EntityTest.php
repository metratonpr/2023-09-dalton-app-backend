<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Entity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EntityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Deve listar entidades com sucesso.
     */
    public function test_listar_entidades_com_sucesso()
    {
        // Cria 10 entidades usando a fábrica
        Entity::factory()->count(10)->create();

        // Faz uma requisição GET para a rota de listagem de entidades
        $response = $this->getJson('/api/entities');

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
     * Deve criar uma entidade com sucesso.
     */
    public function test_criar_entidade_com_sucesso()
    {
        // Cria uma nova entidade usando a fábrica e transforma em um array
        $newData = Entity::factory()->make()->toArray();

        // Faz uma requisição POST para a rota de criação de entidade
        $response = $this->postJson('/api/entities', $newData);

        // Verifica se a resposta tem o status HTTP 201 (Created)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao criar uma entidade vazia.
     */
    public function test_falhar_criar_entidade_vazia()
    {
        // Faz uma requisição POST para a rota de criação de entidade com dados vazios
        $response = $this->postJson('/api/entities', []);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve falhar ao criar uma entidade com o mesmo nome.
     */
    public function test_falhar_criar_entidade_mesmo_nome()
    {
        // Cria uma entidade usando a fábrica
        $existingEntity = Entity::factory()->create();

        // Cria uma nova entidade usando a fábrica e define o mesmo nome da entidade existente
        $newData = Entity::factory()->make(['name' => $existingEntity->name])->toArray();

        // Faz uma requisição POST para a rota de criação de entidade com nome duplicado
        $response = $this->postJson('/api/entities', $newData);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve exibir uma entidade com sucesso.
     */
    public function test_exibir_entidade_com_sucesso()
    {
        // Cria uma entidade usando a fábrica
        $entity = Entity::factory()->create();

        // Faz uma requisição GET para a rota de exibição da entidade
        $response = $this->getJson('/api/entities/' . $entity->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        // Verifica se o nome da entidade na resposta corresponde ao nome da entidade criada
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson(['name' => $entity->name]);
    }

    /**
     * Deve falhar ao exibir uma entidade inexistente.
     */
    public function test_falhar_exibir_entidade_inexistente()
    {
        // Faz uma requisição GET para a rota de exibição de uma entidade inexistente
        $response = $this->getJson('/api/entities/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Entidade não encontrada.']);
    }

    /**
     * Deve atualizar uma entidade com sucesso.
     */
    public function test_atualizar_entidade_com_sucesso()
    {
        // Cria uma entidade usando a fábrica
        $entity = Entity::factory()->create();

        // Cria dados atualizados usando a fábrica
        $updatedData = Entity::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização da entidade
        $response = $this->putJson('/api/entities/' . $entity->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao atualizar uma entidade inexistente.
     */
    public function test_falhar_atualizar_entidade_inexistente()
    {
        // Cria dados atualizados usando a fábrica
        $updatedData = Entity::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização de uma entidade inexistente
        $response = $this->putJson('/api/entities/9999999', $updatedData);

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Entidade não encontrada.']);
    }

    /**
     * Deve falhar ao excluir uma entidade com endereços associados.
     */
    public function test_falhar_excluir_entidade_com_enderecos_associados()
    {
        // Cria uma entidade usando a fábrica
        $entity = Entity::factory()->create();
        $address = Address::factory()->make()->toArray();

        // Simula a existência de endereços associados
        $entity->addresses()->create($address);

        // Faz uma requisição DELETE para a rota de exclusão da entidade
        $response = $this->deleteJson('/api/entities/' . $entity->id);

        // Verifica se a resposta tem o status HTTP 400 (Bad Request)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(400)
            ->assertJson(['error' => 'Esta entidade possui endereços associados e não pode ser excluída.']);
    }

    /**
     * Deve excluir uma entidade com sucesso.
     */
    public function test_excluir_entidade_com_sucesso()
    {
        // Cria uma entidade usando a fábrica
        $entity = Entity::factory()->create();

        // Faz uma requisição DELETE para a rota de exclusão da entidade
        $response = $this->deleteJson('/api/entities/' . $entity->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a mensagem de sucesso retornada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Entidade deletada com sucesso.']);
    }

    /**
     * Deve falhar ao excluir uma entidade inexistente.
     */
    public function test_falhar_excluir_entidade_inexistente()
    {
        // Faz uma requisição DELETE para a rota de exclusão de uma entidade inexistente
        $response = $this->deleteJson('/api/entities/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Entidade não encontrada.']);
    }

    /**
     * Deve permitir atualizar a mesma entidade com o mesmo nome.
     */
    public function test_permitir_atualizar_entidade_com_mesmo_nome_mesmo_id()
    {
        // Cria uma entidade usando a fábrica
        $entity = Entity::factory()->create();

        // Cria dados atualizados com o mesmo nome e o mesmo ID
        $updatedData = $entity->toArray();

        // Tenta atualizar a entidade com os mesmos dados
        $response = $this->putJson('/api/entities/' . $entity->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }
}
