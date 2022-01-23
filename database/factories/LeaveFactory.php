<?php

namespace Database\Factories;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'leave_type_id' => LeaveType::all()->random()->id,
            'number'        => $this->faker->randomDigitNot(0),
            'description'   => $this->faker->text(),
            'start_date'    => $this->faker->dateTimeBetween('+1 week', '+4 week'),
            'end_date'      => $this->faker->dateTimeBetween('+1 week', '+4 week'),
            'status'        => $this->faker->randomElement(['approved', 'pending', 'refused']),
            'type'          => $this->faker->randomElement(['allocation', 'leave']),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Leave $leave) {
            if ($leave->type == 'allocation') {
                $leave->start_date = null;
                $leave->end_date   = null;
                $leave->status     = 'approved';
            } else {
                $leave->approved_by = User::all()->random()->id;
            }
        });
    }
}
