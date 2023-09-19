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
            'name' => $this->faker->word(), 
            'cpf_cnpj' => $this->faker->randomNumber() * 1000, 
            'rg_ie' => $this->faker->randomNumber() * 5000,  
            'email' => $this->faker->email(),  
            'phone' => $this->faker->phoneNumber()
        ];
    }
}
