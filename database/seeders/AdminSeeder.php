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
        $user = User::create([
        'nama' => 'Sakti Hidayat',
        'username' => 'adminsakti123',
        'password' => Hash::make('adminsakti123'),
        'jenis_kelamin' => 'L',
]);

$user->assignRole('Admin');
    }
}
