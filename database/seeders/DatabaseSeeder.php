<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Kategori;
use App\Models\Kios;
use App\Models\Pasar;
use App\Models\Produk;
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
        User::create([
            'id' => 3,
            'name' => 'Keyndra',
            'email' => 'keyndra@gmail.com',
            'password' => Hash::make('keyndra123'),
            'role' => 'pedagang',
            'nomor_telepon' => '081234567890',
        ]);
        User::create([
            'id' => 4,
            'name' => 'Adi',
            'email' => 'adi@gmail.com',
            'password' => Hash::make('adi12345'),
            'role' => 'user',
            'nomor_telepon' => '089463527393'
            
        ]);
        
        User::create([
            'id' => 6,
            'name' => 'Rama Sutisna',
            'email' => 'rama@gmail.com',
            'password' => Hash::make('rama12345'),
            'role' => 'driver',
            'nomor_telepon' => '081234567890',
        ]);
        Driver::create([
                'id' => 1,
                'user_id' => 6,
                'nomor_kendaraan' => 'B 1234 XYZ',
                'jenis_kendaraan' => 'Motor',
                'nomor_stnk' => 'STNK123456',
                'nomor_sim' => 'SIM123456',
                'foto_ktp' => 'ktp_rama.jpg',
                'foto_sim' => 'sim_rama.jpg',
                'foto_stnk' => 'stnk_rama.jpg',
                'foto_kendaraan' => 'motor_rama.jpg',
                'foto_diri' => 'rama.jpg',
                'status' => Driver::STATUS_APPROVED,
            ]);

         Pasar::create([
            'id' => 1,
            'nama_pasar' => 'Pasar Rancamanyar',
            'alamat' => 'Jl. Pasar No.1, Kota',
            'longitude' => '107.5949018',
            'latitude' => '-6.9854158',
            'foto_pasar' => 'pasar.png',
            'ongkir' => 2000,
            'minimal_ongkir' => 5000,
            'biaya_layanan' => 500,
            'biaya_berat_barang' => 2000,
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
            'deskripsi' => 'Kios jualan pakaian.',
            'foto_kios' => '',
            'jam_buka' => '08:00:00',
            'jam_tutup' => '20:00:00'
        ]);

        Produk::create([
            'nama_produk' => 'Keripik Singkong',
            'harga' => 15000,
            'berat_satuan' => 200,
            'stok' => 50,
            'kios_id' => 1,
            'kategori_id' => 1,
            'foto' => '',
            'deskripsi' => 'Keripik singkong renyah dan gurih.'
        ]);

       

        
    }
}
