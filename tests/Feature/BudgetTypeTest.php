<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\BudgetType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BudgetTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Deve listar tipos de orçamento com sucesso.
     */
    public function test_listar_tipos_de_orcamento_com_sucesso()
    {
        // Cria 10 tipos de orçamento usando a fábrica
        BudgetType::factory()->count(10)->create();

        // Faz uma requisição GET para a rota de listagem de tipos de orçamento
        $response = $this->getJson('/api/budget-types');

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
     * Deve criar um tipo de orçamento com sucesso.
     */
    public function test_criar_tipo_de_orcamento_com_sucesso()
    {
        // Cria um novo tipo de orçamento usando a fábrica e transforma em um array
        $newData = BudgetType::factory()->make()->toArray();

        // Faz uma requisição POST para a rota de criação de tipo de orçamento
        $response = $this->postJson('/api/budget-types', $newData);

        // Verifica se a resposta tem o status HTTP 201 (Created)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao criar um tipo de orçamento vazio.
     */
    public function test_falhar_criar_tipo_de_orcamento_vazio()
    {
        // Faz uma requisição POST para a rota de criação de tipo de orçamento com dados vazios
        $response = $this->postJson('/api/budget-types', []);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve falhar ao criar um tipo de orçamento com o mesmo nome.
     */
    public function test_falhar_criar_tipo_de_orcamento_mesmo_nome()
    {
        // Cria um tipo de orçamento usando a fábrica
        $existingBudgetType = BudgetType::factory()->create();

        // Cria um novo tipo de orçamento usando a fábrica e define o mesmo nome do tipo existente
        $newData = BudgetType::factory()->make(['name' => $existingBudgetType->name])->toArray();

        // Faz uma requisição POST para a rota de criação de tipo de orçamento com nome duplicado
        $response = $this->postJson('/api/budget-types', $newData);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve exibir um tipo de orçamento com sucesso.
     */
    public function test_exibir_tipo_de_orcamento_com_sucesso()
    {
        // Cria um tipo de orçamento usando a fábrica
        $budgetType = BudgetType::factory()->create();

        // Faz uma requisição GET para a rota de exibição do tipo de orçamento
        $response = $this->getJson('/api/budget-types/' . $budgetType->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        // Verifica se o nome do tipo de orçamento na resposta corresponde ao nome do tipo criado
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson(['name' => $budgetType->name]);
    }

    /**
     * Deve falhar ao exibir um tipo de orçamento inexistente.
     */
    public function test_falhar_exibir_tipo_de_orcamento_inexistente()
    {
        // Faz uma requisição GET para a rota de exibição de um tipo de orçamento inexistente
        $response = $this->getJson('/api/budget-types/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Tipo de Orçamento não encontrado.']);
    }

    /**
     * Deve atualizar um tipo de orçamento com sucesso.
     */
    public function test_atualizar_tipo_de_orcamento_com_sucesso()
    {
        // Cria um tipo de orçamento usando a fábrica
        $budgetType = BudgetType::factory()->create();

        // Cria dados atualizados usando a fábrica
        $updatedData = BudgetType::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização do tipo de orçamento
        $response = $this->putJson('/api/budget-types/' . $budgetType->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao atualizar um tipo de orçamento inexistente.
     */
    public function test_falhar_atualizar_tipo_de_orcamento_inexistente()
    {
        // Cria dados atualizados usando a fábrica
        $updatedData = BudgetType::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização de um tipo de orçamento inexistente
        $response = $this->putJson('/api/budget-types/9999999', $updatedData);

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Tipo de Orçamento não encontrado.']);
    }

    /**
     * Deve falhar ao excluir um tipo de orçamento com orçamentos associados.
     */
    public function test_falhar_excluir_tipo_de_orcamento_com_orcamentos_associados()
    {
        // Cria um tipo de orçamento usando a fábrica
        $budgetType = BudgetType::factory()->create();

        // Cria um orçamento usando a fábrica associado ao tipo de orçamento
        $budget = Budget::factory()->create(['budget_type_id' => $budgetType->id]);

        // Faz uma requisição DELETE para a rota de exclusão do tipo de orçamento
        $response = $this->deleteJson('/api/budget-types/' . $budgetType->id);

        // Verifica se a resposta tem o status HTTP 400 (Bad Request)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(400)
            ->assertJson(['error' => 'Este Tipo de Orçamento possui orçamentos ou lojas associadas e não pode ser excluído.']);
    }

    /**
     * Deve excluir um tipo de orçamento com sucesso.
     */
    public function test_excluir_tipo_de_orcamento_com_sucesso()
    {
        // Cria um tipo de orçamento usando a fábrica
        $budgetType = BudgetType::factory()->create();

        // Faz uma requisição DELETE para a rota de exclusão do tipo de orçamento
        $response = $this->deleteJson('/api/budget-types/' . $budgetType->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a mensagem de sucesso retornada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Tipo de Orçamento deletado com sucesso.']);
    }

    /**
     * Deve falhar ao excluir um tipo de orçamento inexistente.
     */
    public function test_falhar_excluir_tipo_de_orcamento_inexistente()
    {
        // Faz uma requisição DELETE para a rota de exclusão de um tipo de orçamento inexistente
        $response = $this->deleteJson('/api/budget-types/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Tipo de Orçamento não encontrado.']);
    }
}
