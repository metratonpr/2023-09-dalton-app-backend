<?php

namespace Tests\Feature;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Testa a listagem de países.
     *
     * @return void
     */
    public function test_listagem_de_paises()
    {
        // Crie alguns países usando o Factory
        Country::factory()->count(5)->create();

        // Faça uma requisição GET para a rota que lista os países
        $response = $this->getJson('/api/countries');

        // Verifique se a resposta tem status 200 (OK)
        $response->assertStatus(200);

        // Verifique se o número de países na resposta é igual ao número criado pelo Factory (5)
        $response->assertJsonCount(5, 'data');

        // Verifique a estrutura dos dados retornados
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    /**
     * Testa a criação de um país com sucesso.
     *
     * @return void
     */
    public function test_criacao_de_pais_com_sucesso()
    {
        // Crie dados aleatórios para um país usando o Factory
        $countryData = [
            'name' => $this->faker->country,
        ];

        // Faça uma requisição POST para a rota de criação de países
        $response = $this->postJson('/api/countries', $countryData);

        // Verifique se a resposta tem status 201 (Created)
        $response->assertStatus(201);

        // Verifique se a estrutura dos dados retornados está correta
        $response->assertJsonStructure([
            'id',
            'name',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Testa a criação de um país com falha.
     *
     * @return void
     */
    public function test_criacao_de_pais_com_falha()
    {
        // Crie dados inválidos para um país usando o Factory
        $countryData = [
            'name' => '', // Nome em branco, o que deve falhar na validação.
        ];

        // Faça uma requisição POST para a rota de criação de países
        $response = $this->postJson('/api/countries', $countryData);

        // Verifique se a resposta tem status 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Verifique se há erros de validação para o campo "name"
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Testa a exibição de um país específico.
     *
     * @return void
     */
    public function test_exibicao_de_pais_sucesso()
    {
        // Crie um país usando o Factory
        $country = Country::factory()->create();

        // Faça uma requisição GET para a rota que exibe um país específico
        $response = $this->getJson('/api/countries/' . $country->id);

        // Verifique se a resposta tem status 200 (OK)
        $response->assertStatus(200);

        // Verifique se os dados retornados correspondem aos dados do país criado
        $response->assertJson([
            'id' => $country->id,
            'name' => $country->name,
        ]);
    }

    /**
     * Testa a exibição de um país inexistente.
     *
     * @return void
     */
    public function test_exibicao_de_pais_inexistente()
    {
        // Faça uma requisição GET para a rota que exibe um país inexistente
        $response = $this->getJson('/api/countries/999999');

        // Verifique se a resposta tem status 404 (Not Found)
        $response->assertStatus(404);

        // Verifique se a mensagem de erro é retornada
        $response->assertJson([
            'error' => 'País não encontrado.',
        ]);
    }

    /**
     * Testa a atualização de um país com sucesso.
     *
     * @return void
     */
    public function test_atualizacao_de_pais_com_sucesso()
    {
        // Crie um país usando o Factory
        $country = Country::factory()->create();

        // Crie novos dados aleatórios para o país
        $newCountryData = [
            'name' => $this->faker->country,
        ];

        // Faça uma requisição PUT para a rota de atualização do país
        $response = $this->putJson('/api/countries/' . $country->id, $newCountryData);

        // Verifique se a resposta tem status 200 (OK)
        $response->assertStatus(200);

        // Verifique se os dados retornados correspondem aos novos dados do país
        $response->assertJson([
            'id' => $country->id,
            'name' => $newCountryData['name'],
        ]);
    }

    /**
     * Testa a atualização de um país com falha de validação.
     *
     * @return void
     */
    public function test_atualizacao_de_pais_com_falha_de_validacao()
    {
        // Crie um país usando o Factory
        $country = Country::factory()->create();

        // Crie novos dados inválidos para o país (nome em branco)
        $newCountryData = [
            'name' => '',
        ];

        // Faça uma requisição PUT para a rota de atualização do país
        $response = $this->putJson('/api/countries/' . $country->id, $newCountryData);

        // Verifique se a resposta tem status 422 (Unprocessable Entity)
        $response->assertStatus(422);

        // Verifique se há erros de validação para o campo "name"
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Testa a exclusão de um país com sucesso.
     *
     * @return void
     */
    public function test_exclusao_de_pais_com_sucesso()
    {
        // Crie um país usando o Factory
        $country = Country::factory()->create();

        // Faça uma requisição DELETE para a rota de exclusão do país
        $response = $this->deleteJson('/api/countries/' . $country->id);

        // Verifique se a resposta tem status 200 (OK)
        $response->assertStatus(200);

        // Verifique se a mensagem de exclusão foi retornada
        $response->assertJson([
            'message' => 'País deletado com sucesso.',
        ]);
    }

    /**
     * Testa a exclusão de um país inexistente.
     *
     * @return void
     */
    public function test_exclusao_de_pais_inexistente()
    {
        // Faça uma requisição DELETE para a rota de exclusão de um país inexistente
        $response = $this->deleteJson('/api/countries/999999');

        // Verifique se a resposta tem status 404 (Not Found)
        $response->assertStatus(404);

        // Verifique se a mensagem de erro é retornada
        $response->assertJson([
            'error' => 'País não encontrado.',
        ]);
    }
}
