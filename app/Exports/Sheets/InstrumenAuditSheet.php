<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InstrumenAuditSheet implements
    FromArray,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    protected $audit;

    public function __construct($audit)
    {
        $this->audit = $audit;
    }

    public function array(): array
    {
        $rows = [];

        $rows[] = ['INSTRUMEN AUDIT SPMI 2021'];
        $rows[] = [];

        $rows[] = ['Periode', $this->audit->periode];
        $rows[] = ['Tanggal Audit', $this->audit->tanggal_audit];
        $rows[] = ['Unit', $this->audit->unit->nama ?? '-'];

        $rows[] = [
            'Wakil Auditi',
            $this->audit->wakilAuditi->name ?? '-'
        ];

        $rows[] = [
            'Auditor 1',
            $this->audit->auditor1->name ?? '-'
        ];

        $rows[] = [
            'Auditor 2',
            $this->audit->auditor2->name ?? '-'
        ];

        $rows[] = [];

        $rows[] = [
            'Kode Standar',
            'Temuan',
            'Hasil AMI',
            'Tindakan Perbaikan',
            'Bukti',
            'Tanggapan Auditor 1',
            'Tanggapan Auditor 2',
            'Status',
        ];

        foreach ($this->audit->temuan as $t) {

            $rows[] = [
                $t->kode_indikator,
                $t->temuan,
                $t->hasil_ami,
                $t->tindakan_perbaikan_awal,
                $t->bukti_link,
                $t->tanggapan_auditor,
                $t->tanggapan_auditor_2,
                $t->status,
            ];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Instrumen Audit SPMI 2021';
    }

    public function styles(Worksheet $sheet)
    {
        return [

            // JUDUL
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],

            // HEADER TABEL
            10 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;

                /*
                |--------------------------------------------------------------------------
                | MERGE TITLE
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:H1');

                /*
                |--------------------------------------------------------------------------
                | CENTER TITLE
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1:H1')
                    ->getAlignment()
                    ->setHorizontal('center');

                /*
                |--------------------------------------------------------------------------
                | HEADER TABLE COLOR
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A10:H10')
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('D9EAF7');

                /*
                |--------------------------------------------------------------------------
                | BORDER TABLE
                |--------------------------------------------------------------------------
                */

                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A10:H{$lastRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | STATUS COLOR
                |--------------------------------------------------------------------------
                */

                for ($i = 11; $i <= $lastRow; $i++) {

                    $status = $sheet->getCell("H{$i}")->getValue();

                    // OPEN
                    if ($status == 'OPEN') {

                        $sheet->getStyle("H{$i}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('F8D7DA');
                    }

                    // CLOSED
                    if ($status == 'CLOSED') {

                        $sheet->getStyle("H{$i}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('D4EDDA');
                    }
                }
            },
        ];
    }
}
