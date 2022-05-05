<?php

namespace Database\Seeders;

use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(1)
            ->state(['email' => 'admin@test.com'])
            ->createQuietly()
            ->each(function ($user) {
                $user->assignRole('admin');
            });

        User::factory()
            ->count(1)
            ->state(['email' => 'driver@test.com'])
            ->createQuietly()
            ->each(function ($user) {
                $user->assignRole('driver');
            });
    }
}
