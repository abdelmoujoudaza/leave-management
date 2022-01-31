<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            ['name' => 'employee'],
            ['name' => 'manager'],
            ['name' => 'admin']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
