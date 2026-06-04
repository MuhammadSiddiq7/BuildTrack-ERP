<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if not exist
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $hrRole = Role::firstOrCreate(['name' => 'HR', 'guard_name' => 'web']);
        $mpRole = Role::firstOrCreate(['name' => 'Manager Procurement', 'guard_name' => 'web']);
        $smRole = Role::firstOrCreate(['name' => 'Store Manager', 'guard_name' => 'web']);
        $pmRole = Role::firstOrCreate(['name' => 'Project Manager', 'guard_name' => 'web']);
        $consultantRole = Role::firstOrCreate(['name' => 'Consultant', 'guard_name' => 'web']);
        $clientRole = Role::firstOrCreate(['name' => 'Client', 'guard_name' => 'web']);
        $peRole = Role::firstOrCreate(['name' => 'Planning Engineer', 'guard_name' => 'web']);
        $qsRole = Role::firstOrCreate(['name' => 'Quality Supervisor', 'guard_name' => 'web']);

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('adcc@123'),
                'department' => 'CEO',
            ]
        );
        $admin->assignRole($adminRole);

        // Create HR user
        $hr = User::firstOrCreate(
            ['email' => 'hr@gmail.com'],
            [
                'name' => 'HR',
                'password' => bcrypt('hr@123'),
                'status' => 'active',
                'department' => 'hr',
            ]
        );
        $hr->assignRole($hrRole);

        $mp = User::firstOrCreate(
            ['email' => 'mp.adcc@gmail.com'],
            [
                'name' => 'Manager Procurement',
                'password' => bcrypt('123456'),
                'status' => 'active',
                'department' => 'Manager-of-Procurement',
            ]
        );
        $mp->assignRole($mpRole);

        $sm = User::firstOrCreate(
            ['email' => 'store.adcc@gmail.com'],
            [
                'name' => 'Store Manager',
                'password' => bcrypt('123456'),
                'status' => 'active',
                'department' => 'Store-Manager',
            ]
        );
        $sm->assignRole($smRole);

        $pm = User::firstOrCreate(
            ['email' => 'pm.adcc@gmail.com'],
            [
                'name' => 'Project Manager',
                'password' => bcrypt('123456'),
                'status' => 'active',
                'department' => 'Project-Manager',
            ]
        );
        $pm->assignRole($pmRole);

        $consultant = User::firstOrCreate(
            ['email' => 'consultant.adcc@gmail.com'],
            [
                'name' => 'Consultant',
                'password' => bcrypt('123456'),
                'status' => 'active',
                'department' => 'Consultant',
            ]
        );
        $consultant->assignRole($consultantRole);

        $client = User::firstOrCreate(
            ['email' => 'client.adcc@gmail.com'],
            [
                'name' => 'Client',
                'password' => bcrypt('123456'),
                'status' => 'active',
                'department' => 'Client',
            ]
        );
        $client->assignRole($clientRole);

        $pe = User::firstOrCreate(
            ['email' => 'pe.adcc@gmail.com'],
            [
                'name' => 'Planning Engineer',
                'password' => bcrypt('123456'),
                'status' => 'active',
                'department' => 'Planning-Engineer',
            ]
        );
        $pe->assignRole($peRole);

        $qs = User::firstOrCreate(
            ['email' => 'qs.adcc@gmail.com'],
            [
                'name' => 'Quality Supervisor',
                'password' => bcrypt('123456'),
                'status' => 'active',
                'department' => 'Quality-Supervisor',
            ]
        );
        $qs->assignRole($qsRole);

    }

}
