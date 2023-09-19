<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'description' => $this->faker->sentence(),
            'warranty' => $this->faker->boolean(),
            'warranty_time' => $this->faker->randomNumber($min = 30, $max=90),
            'product_type_id' => function () {
                return ProductType::factory()->create()->id;
            },
        ];
    }
}
