<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ZipCode>
 */
class ZipCodeFactory extends Factory
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
            'zipcode' => $this->faker->unique()->numerify('########'), 
            'place' => $this->faker->word(), 
            'city_id' => function () {
                return City::factory()->create()->id;
            },
            'neighborhood_id' => function () {
                return Neighborhood::factory()->create()->id;
            },
        ];
    }
}
