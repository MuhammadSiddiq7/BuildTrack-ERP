<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  // RolesTableSeeder.php
    public function run(): void
    {
        $roles = [
            'Admin',
            'Store Admin',
            'Storekeeper',
            'Site Supervisor',
            'Project Manager',
            'Senior Project Manager',
            'Warehouse',
            'Head Office',
            'CEO',
            'Client',
            'Quality Supervisor',
            'Planning Engineer',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

}
