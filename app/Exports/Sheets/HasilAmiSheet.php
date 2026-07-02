<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class HasilAmiSheet implements WithTitle
{
    protected $audit;

    public function __construct($audit)
    {
        $this->audit = $audit;
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['HASIL AMI TINDAK LANJUT'];
        $rows[] = [];

        $rows[] = ['[V] Internal Audit'];
        $rows[] = ['[ ] Eksternal Audit'];
        $rows[] = ['[ ] Tinjauan Manajemen'];
        $rows[] = ['[ ] Keluhan Pelanggan'];

        $rows[] = [];

        $rows[] = [
            'Lokasi',
            $this->audit->unit->nama ?? '-'
        ];

        $rows[] = [
            'Tanggal',
            $this->audit->tanggal_audit
        ];

        $rows[] = [];

        $rows[] = [
            'No',
            'Temuan',
            'Penyebab Ketidaksesuaian',
            'Tindakan Perbaikan',
            'Tindakan Pencegahan',
            'Due Date',
            'Status',
        ];

        foreach ($this->audit->temuan as $t) {

            $rows[] = [

                $t->kode_indikator,

                $t->temuan,

                $t->hasil_ami,

                $t->tanggapan_auditor,

                $t->status == 'OPEN'
                ? 'Monev Prodi'
                : '',

                $t->status == 'OPEN'
                ? $this->audit->periode
                : '-',

                $t->status,
            ];
        }

        $rows[] = [];
        $rows[] = [];

        $rows[] = [
            'Penanggungjawab/Pelaksana/Auditee',
            '',
            '',
            '',
            'Pengawas/Auditor'
        ];

        $rows[] = [];
        $rows[] = [];
        $rows[] = [];

        $rows[] = [
            $this->audit->wakilAuditi->name ?? '-',
            '',
            '',
            '',
            $this->audit->auditor1->name ?? '-'
        ];

        return $rows;
    }

    public function title(): string
    {
        return 'Hasil AMI Tindak Lanjut';
    }
}
