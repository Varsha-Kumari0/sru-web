<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'SRU Admin',
            'email' => 'admin@sru.edu.in',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);
    }
}
