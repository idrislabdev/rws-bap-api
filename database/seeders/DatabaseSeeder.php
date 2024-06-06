<?php

namespace Database\Seeders;

use App\Models\MaPengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        MaPengguna::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'Root', 
            'username' => 'root',
            'password' => Hash::make('root'),
            'role' => 'root',
            'status'=> 'AKTIF'
        ]);

        MaPengguna::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'Admin', 
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'status'=> 'AKTIF'
        ]);

        $this->call([
            PengaturanSeeder::class,
            PenggunaSeeder::class,
            WitelSeeder::class
        ]);
    }
}
