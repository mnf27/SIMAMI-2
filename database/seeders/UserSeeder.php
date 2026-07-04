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

        $users = [
            // ASESOR
            [
                'name' => 'Asesor Lead',
                'email' => 'asesor@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $asesor->id ?? null,
                'unit_id' => null,
            ],
            [
                'name' => 'Asesor 1',
                'email' => 'asesor1@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $asesor->id ?? null,
                'unit_id' => null,
            ],
            [
                'name' => 'Asesor 2',
                'email' => 'asesor2@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $asesor->id ?? null,
                'unit_id' => null,
            ],
            [
                'name' => 'Asesor 3',
                'email' => 'asesor3@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $asesor->id ?? null,
                'unit_id' => null,
            ],
            // ADMIN PRODI
            [
                'name' => 'Admin Prodi',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $admin->id ?? null,
                'unit_id' => $prodi->id ?? null,
            ],
            // KPS
            [
                'name' => 'Kepala Prodi',
                'email' => 'kps@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $kps->id ?? null,
                'unit_id' => $prodi->id ?? null,
            ],
            // DOSEN
            [
                'name' => 'Dosen TI',
                'email' => 'dosen@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $dosen->id ?? null,
                'unit_id' => $prodi->id ?? null,
            ],
            // TEKNISI LAB
            [
                'name' => 'Teknisi Lab',
                'email' => 'teknisi@mail.com',
                'password' => Hash::make('password'),
                'role_id' => $teknisi->id ?? null,
                'unit_id' => $lab->id ?? null,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'role_id' => $userData['role_id'],
                    'unit_id' => $userData['unit_id'],
                ]
            );
        }
    }
}
