<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin= User::create([
            'name' => 'yossef amead',
            'email' => 'admin@tingis.com',
            'password' => Hash::make('password'),
            'role_id'=>1
        ]);


        $employee= User::create([
            'name' => 'ereen yeager',
            'email' => 'employee@tingis.com',
            'password' => Hash::make('password'),
            'role_id'=>2
        ]);

    }
}
