<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'     => $this->faker->name(),
            // 'unit'     => $this->faker->randomElement(['day', 'hour']),
            'limited'  => $this->faker->boolean(),
            'balanced' => $this->faker->boolean(),
            'limit'    => $this->faker->randomDigitNot(0),
        ];
    }
}
