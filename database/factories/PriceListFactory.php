<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PriceList>
 */
class PriceListFactory extends Factory
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
            'price' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0.0, $max = 100.00),
            'isAvailable' => $this->faker->boolean(),
            'store_id' => function () {
                return Store::factory()->create()->id;
            },
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
        ];
    }
}
