<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Rodrigo',
                'email' => 'rodrigo@teste.com',
                'cpf' => '61914937031',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Edilson',
                'cpf' => '26776220093',
                'email' => 'edilson@teste.com',
                'password' => Hash::make('senha123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now()
            ]
    ]);
    }
}
