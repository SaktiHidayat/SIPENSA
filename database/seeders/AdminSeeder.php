<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'adminsakti123'],
            [
                'nama' => 'Sakti Hidayat',
                'password' => Hash::make('adminsakti123'),
                'jenis_kelamin' => 'L',
                'role' => 'admin',
            ]
        );
    }
}
