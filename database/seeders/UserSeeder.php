<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@admin.es',
                'password' => bcrypt('admin1234'),
            ],
            [
                'name' => 'manuel',
                'email' => 'manu.quintero@formacionactivaprofesional.com',
                'password' => bcrypt('manuel1234'),
            ],
            [
                'name' => 'antonio',
                'email' => 'antonio@formacionactivaprofesional.com',
                'password' => bcrypt('antonio1234'),
            ],
            [
                'name' => 'joel',
                'email' => 'joel.delgado@formacionactivaprofesional.com',
                'password' => bcrypt('joel1234'),
            ],
        ];

        // Crear cada usuario en la base de datos
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
