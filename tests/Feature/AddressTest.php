<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Budget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_should_return_array_with_success()
    {
        Address::factory()->count(5)->create();

        $response = $this->getJson('/api/addresses/');

        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'number', 'complement', 'zipcode_id', 'entity_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_create_new_address_with_success()
    {
        $data = [
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->sentence,
            'zipcode_id' => 1, // Substitua pelo ID válido do CEP
            'entity_id' => 1, // Substitua pelo ID válido da entidade
        ];

        $response = $this->postJson('/api/addresses/', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id', 'number', 'complement', 'zipcode_id', 'entity_id', 'created_at', 'updated_at'
            ]);
    }

    public function test_create_new_address_with_failure()
    {
        $data = [
            'number' => '',
            'complement' => $this->faker->sentence,
            'zipcode_id' => 1, // Substitua pelo ID válido do CEP
            'entity_id' => 1, // Substitua pelo ID válido da entidade
        ];

        $response = $this->postJson('/api/addresses/', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['number']);
    }

    public function test_find_address_by_id_with_success()
    {
        $address = Address::factory()->create();

        $response = $this->getJson('/api/addresses/' . $address->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $address->id,
                'number' => $address->number,
                'complement' => $address->complement,
                'zipcode_id' => $address->zipcode_id,
                'entity_id' => $address->entity_id,
            ]);
    }

    public function test_find_nonexistent_address_by_id()
    {
        $response = $this->getJson('/api/addresses/99999999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => "Endereço não encontrado!"
            ]);
    }

    public function test_update_address_with_success()
    {
        $address = Address::factory()->create();
        $new = [
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->sentence,
            'zipcode_id' => 1, // Substitua pelo ID válido do CEP
            'entity_id' => 1, // Substitua pelo ID válido da entidade
        ];

        $response = $this->putJson('/api/addresses/' . $address->id, $new);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $address->id,
                'number' => $new['number'],
                'complement' => $new['complement'],
                'zipcode_id' => $new['zipcode_id'],
                'entity_id' => $new['entity_id'],
            ]);
    }

    public function test_update_nonexistent_address()
    {
        $new = [
            'number' => $this->faker->buildingNumber,
            'complement' => $this->faker->sentence,
            'zipcode_id' => 1, // Substitua pelo ID válido do CEP
            'entity_id' => 1, // Substitua pelo ID válido da entidade
        ];

        $response = $this->putJson('/api/addresses/999999', $new);

        $response->assertStatus(404)
            ->assertJson([
                'message' => "Endereço não encontrado!"
            ]);
    }

    public function test_update_address_with_failure()
    {
        $address = Address::factory()->create();
        $new = [
            'number' => '',
            'complement' => $this->faker->sentence,
            'zipcode_id' => 1, // Substitua pelo ID válido do CEP
            'entity_id' => 1, // Substitua pelo ID válido da entidade
        ];

        $response = $this->putJson('/api/addresses/' . $address->id, $new);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['number']);
    }

    public function test_delete_address_with_success()
    {
        $address = Address::factory()->create();

        $response = $this->deleteJson('/api/addresses/' . $address->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => "Endereço deletado com sucesso!"
            ]);
    }

    public function test_delete_nonexistent_address()
    {
        $response = $this->deleteJson('/api/addresses/999999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => "Endereço não encontrado!"
            ]);
    }

    public function test_delete_address_with_children_should_fail()
    {
        $address = Address::factory()->create();
        Budget::factory()->create(['address_id' => $address->id]);

        $response = $this->deleteJson('/api/addresses/' . $address->id);

        $response->assertStatus(400)
            ->assertJson([
                'message' => "Este endereço possui dependências e não pode ser removido."
            ]);
    }
}
