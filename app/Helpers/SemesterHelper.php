<?php

namespace App\Helpers;

class SemesterHelper
{
    public static function label($periode)
    {
        if (! $periode) {
            return '';
        }

        [$tahun, $semester] = explode('-', $periode);

        if ($semester == 1) {
            $label = 'Genap';
            $tahunAwal = $tahun - 1;
            $tahunAkhir = $tahun;
        } else {
            $label = 'Ganjil';
            $tahunAwal = $tahun;
            $tahunAkhir = $tahun + 1;
        }

        return "({$label} {$tahunAwal}/{$tahunAkhir})";
    }
}