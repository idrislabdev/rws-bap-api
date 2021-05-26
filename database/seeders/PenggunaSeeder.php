<?php

namespace Database\Seeders;

use App\Models\MaPengguna;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MaPengguna::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'RWS', 
            'username' => 'rws',
            'password' => Hash::make('12345'),
            'role' => 'RWS',
            'status'=> 'AKTIF'
        ]);

        MaPengguna::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'WITEL', 
            'username' => 'witel',
            'password' => Hash::make('12345'),
            'role' => 'WITEL',
            'status'=> 'AKTIF'
        ]);

        MaPengguna::create([
            'id' => Uuid::uuid1()->toString(),
            'nama_lengkap' => 'MSO', 
            'username' => 'mso',
            'password' => Hash::make('12345'),
            'role' => 'MSO',
            'status'=> 'AKTIF'
        ]);
    }
}
