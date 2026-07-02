<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ambil role
        $asesor = Role::where('nama', 'ASESOR')->first();
        $admin = Role::where('nama', 'ADMIN_PRODI')->first();
        $kps = Role::where('nama', 'KPS')->first();
        $dosen = Role::where('nama', 'DOSEN')->first();
        $teknisi = Role::where('nama', 'TEKNISI')->first();

        // ambil unit
        $prodi = Unit::where('jenis', 'PRODI')->first();
        $lab = Unit::where('jenis', 'LAB')->first();

        // ======================
        // ASESOR
        // ======================
        User::create([
            'name' => 'Asesor Lead',
            'email' => 'asesor@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $asesor->id,
            'unit_id' => null,
        ]);
        User::create([
            'name' => 'Asesor 1',
            'email' => 'asesor1@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $asesor->id,
            'unit_id' => null,
        ]);
        User::create([
            'name' => 'Asesor 2',
            'email' => 'asesor2@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $asesor->id,
            'unit_id' => null,
        ]);
        User::create([
            'name' => 'Asesor 3',
            'email' => 'asesor3@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $asesor->id,
            'unit_id' => null,
        ]);

        // ======================
        // ADMIN PRODI
        // ======================
        User::create([
            'name' => 'Admin Prodi',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $admin->id,
            'unit_id' => $prodi->id,
        ]);

        // ======================
        // KPS
        // ======================
        User::create([
            'name' => 'Kepala Prodi',
            'email' => 'kps@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $kps->id,
            'unit_id' => $prodi->id,
        ]);

        // ======================
        // DOSEN
        // ======================
        User::create([
            'name' => 'Dosen TI',
            'email' => 'dosen@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $dosen->id,
            'unit_id' => $prodi->id,
        ]);

        // ======================
        // TEKNISI LAB
        // ======================
        User::create([
            'name' => 'Teknisi Lab',
            'email' => 'teknisi@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $teknisi->id,
            'unit_id' => $lab->id,
        ]);
    }
}
