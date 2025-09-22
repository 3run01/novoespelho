<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'teste',
            'email' => 'teste@teste.com',
            'password' => Hash::make('12345678'),
            'matricula' => '12331123'
        ]);

        
    }
}