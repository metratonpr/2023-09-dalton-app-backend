<?php

namespace Tests\Feature;

use App\Models\Neighborhood;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NeighborhoodTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Deve criar 10 bairros e retornar
     * @return void
     */
    public function test_salvar_pesquisar_bairro_banco_dados()
    {
        //Preparar os dados ou parametros
        Neighborhood::factory()->count(10)->create();
        //Processar
        $response = $this->getJson('/api/neighborhoods');
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at',]
                ]
            ]);
    }

    /**
     * Criar Bairro com sucesso
     */
    public function test_criar_bairro_com_sucesso()
    {    
        // criar bairro
        $newData = Neighborhood::factory()->make()->toArray();
        //Processar
        $response = $this->postJson('/api/neighborhoods', $newData);

        $response->assertStatus(201)
            ->assertJsonStructure(
                ['id', 'name', 'created_at', 'updated_at',]
            );
    }
}
