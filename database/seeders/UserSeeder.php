<?php

namespace Database\Seeders;

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
            ->create()
            ->each(function ($user) {
                $user->assignRole('admin');
            });

        User::factory()
            ->count(1)
            ->state(['email' => 'manager@test.com'])
            ->create()
            ->each(function ($user) {
                $user->assignRole('manager');
            });

        User::factory()
            ->count(10)
            ->hasLeaves(3)
            ->create()
            ->each(function ($user) {
                $user->assignRole('employee');
            });
    }
}
