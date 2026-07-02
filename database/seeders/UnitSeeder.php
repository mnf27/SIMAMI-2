<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::insert([
            [
                'nama' => 'D3 Teknologi Informasi',
                'jenis' => 'PRODI',
                'lokasi' => 'PSDKU Lumajang',
            ],
            [
                'nama' => 'Laboratorium TI',
                'jenis' => 'LAB',
                'lokasi' => 'PSDKU Lumajang',
            ],
        ]);
    }
}
