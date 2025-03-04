<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insere usuários iniciais
        DB::table('users')->insert([
            [
                'name' => 'Rafa',
                'email' => 'rafa@mail.com',
                'password' => Hash::make('rafa12345'), // Senha criptografada
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Renan',
                'email' => 'renan@mail.com',
                'password' => Hash::make('renan12345'), // Senha criptografada
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tiago',
                'email' => 'tiago@mail.com',
                'password' => Hash::make('tiago12345'), // Senha criptografada
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}