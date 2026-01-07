<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin TPAS',
            'email' => 'admin@tpas.local',
            'password' => bcrypt('password'),
        ]);

        $this->command->info('Admin user created successfully.');
        $this->command->info('Email: admin@tpas.local');
        $this->command->info('Password: password');
    }
}
