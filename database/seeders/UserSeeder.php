<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            'nama' => 'John Doe',
            'token' => '0000',
            'password' => '0000',
            'peran' => 'Admin',
        ];

        User::firstOrCreate(['token' => $userData['token']], $userData);

        User::factory()->count(20)->create();
    }
}
