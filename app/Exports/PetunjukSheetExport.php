<?php

namespace App\Exports;

use App\Models\Role;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\FromArray;

class PetunjukSheetExport implements FromArray
{
    public function array(): array
    {
        $rows = [];
        $rows[] = ['PETUNJUK IMPORT USER'];
        $rows[] = [];
        $rows[] = ['Password default seluruh user: simami123'];
        $rows[] = [];
        $rows[] = ['Role yang tersedia'];

        foreach (Role::all() as $role) {
            $rows[] = [$role->nama];
        }

        $rows[] = [];
        $rows[] = ['Unit yang tersedia'];

        foreach (Unit::all() as $unit) {
            $rows[] = [$unit->nama];
        }

        return $rows;
    }
}
