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
            'email' => 'superadmin@tingis.com',
            'password' => Hash::make('password'),
            'role_id'=>1
        ]);


        $employee= User::create([
            'name' => 'ereen yeager',
            'email' => 'employee@tingis.com',
            'password' => Hash::make('password'),
            'role_id'=>2,
            'company_id'=>4
        ]);

        $administrator= User::create([
            'name' => 'sam alert',
            'email' => 'administrator@tingis.com',
            'password' => Hash::make('password'),
            'role_id'=>3,
            'company_id'=>4
        ]);

    }
}
