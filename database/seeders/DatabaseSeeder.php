<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            ConfigurationSeeder::class,
            BranchUserSeeder::class,
            DenominationSeeder::class,
        ]);
    }
}
