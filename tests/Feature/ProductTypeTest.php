<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Deve listar tipos de produtos com sucesso.
     */
    public function test_listar_tipos_de_produtos_com_sucesso()
    {
        // Cria 10 tipos de produtos usando a fábrica
        ProductType::factory()->count(10)->create();

        // Faz uma requisição GET para a rota de listagem de tipos de produtos
        $response = $this->getJson('/api/product-types');

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
     * Deve criar um tipo de produto com sucesso.
     */
    public function test_criar_tipo_de_produto_com_sucesso()
    {
        // Cria um novo tipo de produto usando a fábrica e transforma em um array
        $newData = ProductType::factory()->make()->toArray();

        // Faz uma requisição POST para a rota de criação de tipos de produtos
        $response = $this->postJson('/api/product-types', $newData);

        // Verifica se a resposta tem o status HTTP 201 (Created)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao criar um tipo de produto vazio.
     */
    public function test_falhar_criar_tipo_de_produto_vazio()
    {
        // Faz uma requisição POST para a rota de criação de tipos de produtos com dados vazios
        $response = $this->postJson('/api/product-types', []);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve falhar ao criar um tipo de produto com o mesmo nome.
     */
    public function test_falhar_criar_tipo_de_produto_mesmo_nome()
    {
        // Cria um tipo de produto usando a fábrica
        $existingProductType = ProductType::factory()->create();

        // Cria um novo tipo de produto usando a fábrica e define o mesmo nome do tipo de produto existente
        $newData = ProductType::factory()->make(['name' => $existingProductType->name])->toArray();

        // Faz uma requisição POST para a rota de criação de tipos de produtos com nome duplicado
        $response = $this->postJson('/api/product-types', $newData);

        // Verifica se a resposta tem o status HTTP 422 (Unprocessable Entity)
        // Verifica se há erros de validação no campo 'name'
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Deve exibir um tipo de produto com sucesso.
     */
    public function test_exibir_tipo_de_produto_com_sucesso()
    {
        // Cria um tipo de produto usando a fábrica
        $productType = ProductType::factory()->create();

        // Faz uma requisição GET para a rota de exibição do tipo de produto
        $response = $this->getJson('/api/product-types/' . $productType->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        // Verifica se o nome do tipo de produto na resposta corresponde ao nome do tipo de produto criado
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at'])
            ->assertJson(['name' => $productType->name]);
    }

    /**
     * Deve falhar ao exibir um tipo de produto inexistente.
     */
    public function test_falhar_exibir_tipo_de_produto_inexistente()
    {
        // Faz uma requisição GET para a rota de exibição de um tipo de produto inexistente
        $response = $this->getJson('/api/product-types/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Tipo de Produto não encontrado.']);
    }

    /**
     * Deve atualizar um tipo de produto com sucesso.
     */
    public function test_atualizar_tipo_de_produto_com_sucesso()
    {
        // Cria um tipo de produto usando a fábrica
        $productType = ProductType::factory()->create();

        // Cria dados atualizados usando a fábrica
        $updatedData = ProductType::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização do tipo de produto
        $response = $this->putJson('/api/product-types/' . $productType->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve falhar ao atualizar um tipo de produto inexistente.
     */
    public function test_falhar_atualizar_tipo_de_produto_inexistente()
    {
        // Cria dados atualizados usando a fábrica
        $updatedData = ProductType::factory()->make()->toArray();

        // Faz uma requisição PUT para a rota de atualização de um tipo de produto inexistente
        $response = $this->putJson('/api/product-types/9999999', $updatedData);

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Tipo de Produto não encontrado.']);
    }

    /**
     * Deve permitir atualizar o mesmo cadastro com o mesmo nome.
     */
    public function test_permitir_atualizar_tipo_de_produto_com_mesmo_nome_mesmo_id()
    {
        // Cria um tipo de produto usando a fábrica
        $productType = ProductType::factory()->create();

        // Cria dados atualizados com o mesmo nome e o mesmo ID
        $updatedData = $productType->toArray();

        // Tenta atualizar o tipo de produto com os mesmos dados
        $response = $this->putJson('/api/product-types/' . $productType->id, $updatedData);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a estrutura dos dados retornados
        $response->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'created_at', 'updated_at']);
    }

    /**
     * Deve excluir um tipo de produto com sucesso.
     */
    public function test_excluir_tipo_de_produto_com_sucesso()
    {
        // Cria um tipo de produto usando a fábrica
        $productType = ProductType::factory()->create();

        // Faz uma requisição DELETE para a rota de exclusão do tipo de produto
        $response = $this->deleteJson('/api/product-types/' . $productType->id);

        // Verifica se a resposta tem o status HTTP 200 (OK)
        // Verifica a mensagem de sucesso retornada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Tipo de Produto deletado com sucesso.']);
    }

    /**
     * Deve falhar ao excluir um tipo de produto inexistente.
     */
    public function test_falhar_excluir_tipo_de_produto_inexistente()
    {
        // Faz uma requisição DELETE para a rota de exclusão de um tipo de produto inexistente
        $response = $this->deleteJson('/api/product-types/9999999');

        // Verifica se a resposta tem o status HTTP 404 (Not Found)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(404)
            ->assertJson(['error' => 'Tipo de Produto não encontrado.']);
    }

    /**
     * Deve falhar ao excluir um tipo de produto com relacionamentos (produtos associados).
     */
    public function test_falhar_excluir_tipo_de_produto_com_relacionamentos()
    {
        // Cria um tipo de produto usando a fábrica
        $productType = ProductType::factory()->create();

        // Cria um produto associado ao tipo de produto
        $product = Product::factory()->make()->toArray();
        $productType->products()->create($product);

        // Faz uma requisição DELETE para a rota de exclusão do tipo de produto
        $response = $this->deleteJson('/api/product-types/' . $productType->id);

        // Verifica se a resposta tem o status HTTP 400 (Bad Request)
        // Verifica a mensagem de erro retornada
        $response->assertStatus(400)
            ->assertJson(['error' => 'Este Tipo de Produto possui produtos associados e não pode ser excluído.']);
    }
}
