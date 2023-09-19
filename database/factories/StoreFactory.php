<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
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
            'name' => $this->faker->word(),
            'contact' => $this->faker->word(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'cnpj' => $this->faker->numberBetween($int1 = 1111111111111, $int2 = 9999999999999),
            'number' => $this->faker->numberBetween($int1 = 100, $int2 = 1000),
            'complement' => $this->faker->word(),
            'address_id'  => function () {
                return Address::factory()->create()->id;
            },
        ];
    }
}
