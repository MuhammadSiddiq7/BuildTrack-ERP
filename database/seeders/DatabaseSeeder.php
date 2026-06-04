<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionTableSeeder::class,
            AdminUserSeeder::class,
            RolePermissionSeeder::class,
            RolesTableSeeder::class,
            ProjectsTableSeeder::class,
            taxSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
