<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Dewiga',
            'email' => 'admin@dewiga.com',
            'password' => bcrypt('sujatmikotri'),
            'is_admin' => true,
        ]);
    }
}
