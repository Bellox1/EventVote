<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@vote.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User One',
            'email' => 'user@vote.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $this->call(CampaignSeeder::class);
    }
}
