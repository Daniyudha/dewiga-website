<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HeroSettingSeeder::class,
            ActivitySeeder::class,
            BlogSeeder::class,
            PriceCalculatorSeeder::class,
            RolePermissionSeeder::class,
            DummyDataSeeder::class,
            RundownTemplateSeeder::class,
            ProposalSettingSeeder::class,
        ]);
    }
}