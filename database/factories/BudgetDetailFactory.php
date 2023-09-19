<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BudgetDetail>
 */
class BudgetDetailFactory extends Factory
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
            'amount' => $this->faker->randomNumber(),
            'price' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0.0, $max = 100.00),
            'discount' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0.0, $max = 50.00),
            'subtotal' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0.0, $max = 100.00),
            'budget_id' => function () {
                return Budget::factory()->create()->id;
            },
            'price_list_id' => function () {
                return PriceList::factory()->create()->id;
            },
        ];
    }
}
