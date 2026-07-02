<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class UserSheetExport implements FromArray
{
    public function array(): array
    {
        return [['No', 'Nama', 'Email', 'Role', 'Unit',]];
    }
}