<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Station;
use App\Models\Direction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Station::factory()
            ->count(5)
            ->state(new Sequence(
                fn ($sequence) => ['direction_id' => Direction::all()->random()],
            ))
            ->createQuietly()
            ->each(function ($station) {
                $station->passengers()->saveMany(
                    User::factory()
                    ->count(5)
                    ->createQuietly()
                    ->each(function ($user) {
                        $user->assignRole('student');
                    })
                );
            });
    }
}
