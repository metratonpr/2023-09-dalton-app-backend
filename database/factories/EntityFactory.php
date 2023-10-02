<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entity>
 */
class EntityFactory extends Factory
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
            'name' => $this->faker->unique()->name(), 
            'cpf_cnpj' => $this->faker->unique()->numerify('#############'), 
            'rg_ie' => $this->faker->unique()->numerify('##########'),  
            'email' => $this->faker->unique()->email(),  
            'phone' => $this->faker->phoneNumber()
        ];
    }
}
