<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PTPPSheet implements WithTitle,
    WithEvents
{
    protected $audit;

    public function __construct($audit)
    {
        $this->audit = $audit;
    }

    public function title(): string
    {
        return 'PTPP';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $audit = $this->audit;

                // Header
                $sheet->setCellValue('B11', $audit->unit->nama);
                $sheet->setCellValue(
                    'L12',
                    $audit->tanggal_audit
                );

                // Table
                $row = 19;

                foreach ($audit->temuan as $t) {
                    $sheet->setCellValue(
                        "A$row",
                        $t->kode_indikator
                    );
                    
                    $sheet->setCellValue(
                        "B$row",
                        $t->temuan
                    );

                    $sheet->setCellValue(
                        "C$row",
                        $t->hasil_ami
                    );

                    $row++;
                }
            }
        ];
    }
}