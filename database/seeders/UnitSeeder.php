<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
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
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['nama' => $unit['nama']],
                [
                    'jenis' => $unit['jenis'],
                    'lokasi' => $unit['lokasi'],
                ]
            );
        }
    }
}
