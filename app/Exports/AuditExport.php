<?php

namespace App\Exports;

use App\Models\Audit;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AuditExport implements WithEvents
{
    protected $audit;

    public function __construct($auditId)
    {
        $this->audit = Audit::with([
            'temuan',
            'unit',
            'wakilAuditi',
            'auditor1',
            'auditor2',
        ])->findOrFail($auditId);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $spreadsheet = $event->sheet
                    ->getDelegate()
                    ->getParent();

                // Sheet PTPP
                $sheet = $spreadsheet->getSheet(2);

                $audit = $this->audit;

                // HEADER
                $sheet->setCellValue(
                    'B11',
                    $audit->unit->nama
                );

                $sheet->setCellValue(
                    'L12',
                    $audit->tanggal_audit
                );

                $sheet->setCellValue(
                    'F14',
                    optional($audit->auditor1)->name
                );

                $sheet->setCellValue(
                    'J14',
                    optional($audit->auditor2)->name
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

                    $sheet->setCellValue(
                        "D$row",
                        $t->tanggapan_auditor
                    );

                    $sheet->setCellValue(
                        "M$row",
                        $t->status
                    );

                    $row++;
                }
            }
        ];
    }
}
