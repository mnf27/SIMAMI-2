<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['ADMIN_PRODI', 'KPS', 'DOSEN', 'TEKNISI', 'ASESOR'];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['nama' => $role]);
        }
    }
}
