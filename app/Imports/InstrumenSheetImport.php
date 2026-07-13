<?php

namespace App\Imports;

use App\Models\TemuanAudit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class InstrumenSheetImport implements ToCollection
{
    protected $auditId;

    public function __construct($auditId)
    {
        $this->auditId = $auditId;
    }

    public function collection(Collection $rows)
    {
        $mapping = [];
        $headerRow = null;
        $imported = 0;

        /*
        |--------------------------------------------------------------------------
        | Cari Header
        |--------------------------------------------------------------------------
        */
        foreach ($rows as $index => $row) {

            foreach ($row as $column => $value) {

                $text = strtoupper(trim((string) $value));

                switch ($text) {

                    case 'KODE STANDAR':
                    case 'KODE':
                    case 'KODE INDIKATOR':
                        $mapping['kode'] = $column;
                        break;

                    case 'TEMUAN':
                        $mapping['temuan'] = $column;
                        break;

                    case 'VALIDASI':
                    case 'STATUS':
                        $mapping['status'] = $column;
                        break;
                }
            }

            if (
                isset($mapping['kode']) &&
                isset($mapping['temuan']) &&
                isset($mapping['status'])
            ) {
                $headerRow = $index;
                break;
            }
        }

        if ($headerRow === null) {
            throw new \Exception(
                'Header Kode Standar, Temuan, atau Validasi tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Import Data
        |--------------------------------------------------------------------------
        */
        for ($i = $headerRow + 1; $i < $rows->count(); $i++) {

            $row = $rows[$i];

            $kode = trim((string) ($row[$mapping['kode']] ?? ''));

            $temuan = trim((string) ($row[$mapping['temuan']] ?? ''));

            $status = strtoupper(
                trim((string) ($row[$mapping['status']] ?? ''))
            );

            // Lewati baris kosong
            if ($kode === '' && $temuan === '') {
                continue;
            }

            // Pastikan hanya format indikator
            if (! preg_match('/^\d+\.\d+$/', $kode)) {
                continue;
            }

            // Hanya OPEN
            if ($status !== 'OPEN') {
                continue;
            }

            TemuanAudit::create([
                'audit_id' => $this->auditId,
                'kode_indikator' => $kode,
                'temuan' => $temuan,
                'status' => 'OPEN',
            ]);

            $imported++;
        }

        if ($imported === 0) {
            throw new \Exception(
                'Tidak ditemukan temuan berstatus OPEN.'
            );
        }
    }
}