<?php

namespace App\Imports;

use App\Models\TemuanAudit;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InstrumenSheetImport implements ToCollection
{
    protected $auditId;
    protected $unitId;

    public function __construct($auditId, $unitId)
    {
        $this->auditId = $auditId;
        $this->unitId = $unitId;
    }

    public function collection(Collection $rows)
    {
        $imported = 0;
        foreach ($rows as $row) {
            // skip kosong
            if (empty($row[0]) || empty($row[1])) {
                continue;
            }
            $kode = trim($row[0]);
            // hanya format seperti 3.4
            if (! preg_match('/^\d+\.\d+$/', $kode)) {
                continue;
            }
            $temuan = TemuanAudit::create([
                'audit_id' => $this->auditId,
                'kode_indikator' => $kode,
                'temuan' => $row[1],
                'status' => 'OPEN',
            ]);
            // assign ke user unit
            $users = User::where('unit_id', $this->unitId)->get();
            foreach ($users as $user) {
                $temuan->users()->attach($user->id);
            }
            $imported++;
        }
        if ($imported === 0) {
            throw new \Exception(
                'Format template instrumen tidak sesuai.'
            );
        }
    }
}