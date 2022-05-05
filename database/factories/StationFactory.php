<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'      => $this->faker->streetName(),
            'address'   => $this->faker->address(),
            'latitude'  => $this->faker->latitude(34, 34),
            'longitude' => $this->faker->longitude(-6, -6),
            'status'    => $this->faker->boolean(90)
        ];
    }
}
