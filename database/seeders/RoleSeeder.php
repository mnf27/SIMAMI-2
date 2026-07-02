<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['nama' => 'ADMIN_PRODI'],
            ['nama' => 'KPS'],
            ['nama' => 'DOSEN'],
            ['nama' => 'TEKNISI'],
            ['nama' => 'ASESOR'],
        ]);
    }
}
