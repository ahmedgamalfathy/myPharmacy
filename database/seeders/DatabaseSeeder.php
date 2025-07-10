<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\User\UserStatus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Database\Seeders\User\UserSeeder;
use Database\Seeders\Roles\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
        ]);
      
    }
}
