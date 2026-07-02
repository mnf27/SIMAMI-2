<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['nama']) ||
            empty($row['email'])
        ) {
            return null;
        }

        if (
            User::where('email', trim($row['email']))
                ->exists()
        ) {
            return null;
        }

        $role = Role::where('nama', trim($row['role'] ?? ''))->first();

        if (! $role) {
            return null;
        }

        $unitId = null;

        if (! empty($row['unit'])) {
            $unit = Unit::where('nama', trim($row['unit']))->first();
            $unitId = $unit?->id;
        }

        return new User([
            'name' => trim($row['nama']),
            'email' => trim($row['email']),
            'password' => Hash::make('simami123'),
            'role_id' => $role->id,
            'unit_id' => $unitId,
        ]);
    }
}