<?php

namespace Database\Factories;

use App\Models\Entity;
use App\Models\ZipCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'number' => $this->faker->unique()->randomNumber(),
            'complement' => $this->faker->unique()->word(),
            'zipcode_id'=> function () {
                return ZipCode::factory()->create()->id;
            },
            'entity_id'=> function () {
                return Entity::factory()->create()->id;
            },
        ];
    }
}
