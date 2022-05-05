<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Direction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Direction::factory()
            ->count(1)
            ->state(new Sequence(
                fn ($sequence) => ['driver_id' => User::role('driver')->get()->random()],
            ))
            ->createQuietly();
    }
}
