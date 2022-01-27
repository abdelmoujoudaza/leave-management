<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name'     => 'congés payés',
                'balanced' => true,
                'limited'  => false,
                'limit'    => null
            ],
            [
                'name'     => 'congés sans solde',
                'balanced' => false,
                'limited'  => true,
                'limit'    => 180
            ],
            [
                'name'     => 'arrêts maladie',
                'balanced' => false,
                'limited'  => true,
                'limit'    => 180
            ],
            [
                'name'     => 'congés maternité et congés paternité',
                'balanced' => false,
                'limited'  => true,
                'limit'    => 2
            ],
            [
                'name'     => 'absences pour mariage',
                'balanced' => false,
                'limited'  => true,
                'limit'    => 4
            ],
            [
                'name'     => 'naissance ou décès',
                'balanced' => false,
                'limited'  => true,
                'limit'    => 2
            ],
            [
                'name'     => 'heures supplémentaires',
                'balanced' => true,
                'limited'  => false,
                'limit'    => null
            ]
        ];

        LeaveType::factory()
            ->count(count($types))
            ->state(new Sequence(...$types))
            ->create();
    }
}
