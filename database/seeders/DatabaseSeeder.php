<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Kios;
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
            'id' => 1,
            'nama_pasar' => 'Pasar Tradisional Kota',
            'alamat' => 'Jl. Pasar No.1, Kota',
            'longitude' => '106.845599',
            'latitude' => '-6.208763',
            'foto_pasar' => 'Pajajap.jpg',
            'ongkir' => 10000,
            'kontak' => '081234567890',
            'deskripsi' => 'Pasar tradisional terbesar di kota ini.',
        ]);

        Kategori::create([
            'nama_kategori' => 'Makanan Ringan',
            'deskripsi' => 'Makanan ringan seperti keripik, kue kering, dan camilan lainnya.',
        ]);

         Kategori::create([
            'nama_kategori' => 'Makanan Beku',
            'deskripsi' => 'Contoh : Nugget, bakso, sosis, dan lain-lain.',
        ]);

        Kategori::create([
            'nama_kategori' => 'Minuman',
            'deskripsi' => 'Contoh : Air mineral, jus, minuman bersoda, dan lain-lain.',
        ]);

        Kategori::create([
            'nama_kategori' => 'Sayuran',
            'deskripsi' => 'Contoh : Bayam, kangkung, wortel'
        ]);

        User::create([
            'id' => 2,
            'name' => 'Tate',
            'email' => 'tatemcrae@gmail.com',
            'password' => Hash::make('tate12345'),
            'role' => 'pedagang',
            'nomor_telepon' => '081234567890',
        ]);


        Kios::create([
            'nama_kios' => 'Kios Tate',
            'lokasi' => 'Jl. Pasar No.1, Kota',
            'pasar_id' => 1,
            'user_id' => 2,
            'kontak' => '081234567890',
            'deskripsi' => 'Kios jualan pakaian.',
            'foto_kios' => '',
        ]);

       

        
    }
}
