<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\BudgetType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
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
            'budget_date'  => $this->faker->dateTimeBetween('2023-01-01', '2023-07-01')->format('Y-m-d'),
            'expiration_date' => $this->faker->dateTimeBetween('2023-07-01', '2023-09-01')->format('Y-m-d'),
            'delivery_date' => $this->faker->dateTimeBetween('2023-09-01', '2023-12-31')->format('Y-m-d'),
            'shipping_value' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0.0, $max = 100.00),
            'address_id' => function () {
                return Address::factory()->create()->id;
            },
            'budget_type_id' => function () {
                return BudgetType::factory()->create()->id;
            },
        ];
    }
}
