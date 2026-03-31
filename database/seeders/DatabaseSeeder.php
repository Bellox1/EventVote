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
            'name' => 'Admin Bello',
            'email' => 'mantinoubello123@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->call(CampaignSeeder::class);
    }
}
