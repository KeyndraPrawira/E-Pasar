<?php

namespace Database\Seeders;

use App\Models\Pasar;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@epasar.id',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
            'nomor_telepon' => '081234567890',
        ]);

        Pasar::create([
            'nama_pasar' => 'Pasar Tradisional Kota',
            'alamat' => 'Jl. Pasar No.1, Kota',
            'longitude' => '106.845599',
            'latitude' => '-6.208763',
            'foto_pasar' => 'Pajajap.jpg',
            'ongkir' => 10000,
            'kontak' => '081234567890',
            'deskripsi' => 'Pasar tradisional terbesar di kota ini.',
        ]);

        
    }
}
