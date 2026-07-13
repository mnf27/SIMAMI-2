<?php

namespace App\Services;

use App\Models\Audit;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Services\Spreadsheet\HeaderLocator;

class AuditExportService
{
    protected HeaderLocator $locator;

    public function __construct(HeaderLocator $locator)
    {
        $this->locator = $locator;
    }

    public function export($id)
    {
        $audit = Audit::with(['temuan', 'unit', 'wakilAuditi', 'auditor1', 'auditor2', 'leadAuditor', 'periode',
        ])->findOrFail($id);
        $spreadsheet = $this->loadSpreadsheet($audit);
        $this->fillInstrumenSheet($spreadsheet, $audit);
        $this->fillHasilSheet($spreadsheet, $audit);
        $this->fillPTPPSheet($spreadsheet, $audit);

        return $this->downloadSpreadsheet($spreadsheet, $audit);
    }

    private function loadSpreadsheet(Audit $audit)
    {
        if (empty($audit->instrumen_path)) {
            throw new \Exception('File instrumen belum diunggah.');
        }

        $templatePath = storage_path('app/public/'.$audit->instrumen_path);

        if (! file_exists($templatePath)) {
            throw new \Exception('File instrumen tidak ditemukan.');
        }

        return IOFactory::load($templatePath);
    }

    private function fillInstrumenHeader(Worksheet $sheet, Audit $audit): void
    {
        $sheet->setCellValue('B1', strtoupper($audit->unit->nama ?? '-'));
        $sheet->setCellValue('B2', strtoupper($audit->unit->lokasi ?? '-'));
        $sheet->setCellValue('B3', $this->getDueDate($audit));
        $sheet->setCellValue('C2', $audit->wakilAuditi->name ?? '-');
        $sheet->setCellValue('C3', $this->getFormattedAuditDate($audit));
        $sheet->setCellValue('F5', $audit->auditor1->name ?? '-');
        $sheet->setCellValue('G5', $audit->auditor2->name ?? '-');
    }

    private function fillInstrumenSheet($spreadsheet, Audit $audit): void
    {
        $sheet = $this->getInstrumenSheet($spreadsheet);
        $this->fillInstrumenHeader($sheet, $audit);
        $this->fillInstrumenBody($sheet, $audit);
    }

    private function fillInstrumenBody(Worksheet $sheet, Audit $audit): void
    {
        $headerRow = $this->locator->findHeaderRow($sheet, ['KODE STANDAR', 'TEMUAN']);

        if ($headerRow === null) {
            throw new \Exception('Header Sheet Instrumen tidak ditemukan.');
        }

        $columns = $this->locator->findInstrumenColumns($sheet, $headerRow);
        $row = $headerRow + 1;
        
        foreach ($audit->temuan as $temuan) {
            $sheet->setCellValueByColumnAndRow($columns['kode'], $row, $temuan->kode_indikator);
            $sheet->setCellValueByColumnAndRow($columns['temuan'], $row, $temuan->temuan);
            $sheet->setCellValueByColumnAndRow($columns['hasil_ami'], $row, $temuan->hasil_ami);
            $sheet->setCellValueByColumnAndRow($columns['tindakan'], $row, $temuan->tindakan_perbaikan_awal);
            $link = trim($temuan->bukti_link ?? '');
            $sheet->setCellValueByColumnAndRow($columns['bukti'], $row, $link);

            if (! empty($link)) {
                $sheet->getCellByColumnAndRow($columns['bukti'], $row)->getHyperlink()->setUrl($link);
            }

            $sheet->setCellValueByColumnAndRow($columns['auditor1'], $row, $temuan->tanggapan_auditor);
            $sheet->setCellValueByColumnAndRow($columns['auditor2'], $row, $temuan->tanggapan_auditor_2);
            $sheet->setCellValueByColumnAndRow($columns['status'], $row, $temuan->status);
            $row++;
        }

        $this->trimTable($sheet, $row - 1);
    }

    private function fillHasilSheet($spreadsheet, Audit $audit): void
    {
        $sheet = $this->getHasilSheet($spreadsheet);
        $headerRow = $this->locator->findHeaderRow($sheet, ['NO.', 'TEMUAN']);
        $columns = array_merge($this->locator->findHasilAMIColumns($sheet, $headerRow), $this->locator
            ->findHasilAMIColumns($sheet, $headerRow + 1));
        $row = $headerRow + 2;

        foreach ($audit->temuan as $temuan) {
            $sheet->setCellValueByColumnAndRow($columns['kode'], $row, $temuan->kode_indikator);
            $sheet->setCellValueByColumnAndRow($columns['temuan'], $row, $temuan->temuan);
            $sheet->setCellValueByColumnAndRow($columns['akar'], $row, $temuan->hasil_ami);
            $sheet->setCellValueByColumnAndRow($columns['perbaikan'], $row, $temuan->tindakan_perbaikan_awal);
            $sheet->setCellValueByColumnAndRow($columns['pencegahan'], $row, 'Monev Prodi');
            $sheet->setCellValueByColumnAndRow($columns['duedate'], $row, $this->getDueDate($audit));
            $sheet->setCellValueByColumnAndRow($columns['status'], $row, $temuan->status);
            $row++;
        }

        $footerRow = $this->locator->findFooterRow($sheet, ['PENANGGUNGJAWAB', 'AUDITOR']);
        $this->hideEmptyRowsBeforeFooter($sheet, $row - 1, $footerRow);
    }

    private function fillPTPPSheet($spreadsheet, Audit $audit): void
    {
        $sheet = $this->getPTPPSheet($spreadsheet);
        $headerRow = $this->locator->findHeaderRow($sheet, ['STANDAR', 'INDIKATOR']);
        $columns = $this->locator->findPTPPColumns($sheet, $headerRow);
        $instrumenSheet = $this->getInstrumenSheet($spreadsheet);
        $instrumenHeader = $this->locator->findHeaderRow($instrumenSheet, ['KODE STANDAR', 'TEMUAN']);
        $rowInstrumen = $instrumenHeader + 1;
        $hasilSheet = $this->getHasilSheet($spreadsheet);
        $hasilHeader = $this->locator->findHeaderRow($hasilSheet, ['NO.', 'TEMUAN']);
        $rowHasil = $hasilHeader + 2;
        $rowPTPP = $headerRow + 1;

        foreach ($audit->temuan as $temuan) {
            $sheet->setCellValueByColumnAndRow($columns['kode'], $rowPTPP,
                "='Instrumen Audit SPMI 2021'!A{$rowInstrumen}");
            $sheet->setCellValueByColumnAndRow($columns['indikator'], $rowPTPP,
                "='Instrumen Audit SPMI 2021'!B{$rowInstrumen}");
            $sheet->setCellValueByColumnAndRow($columns['hasil'], $rowPTPP,
                "='Instrumen Audit SPMI 2021'!C{$rowInstrumen}");
            $sheet->setCellValueByColumnAndRow($columns['akar'], $rowPTPP,
                "='Hasil AMI  Tindak lanjut'!C{$rowHasil}");
            $sheet->setCellValueByColumnAndRow($columns['kondisi'], $rowPTPP,
                "='Instrumen Audit SPMI 2021'!H{$rowInstrumen}");
            $rowInstrumen++;
            $rowHasil++;
            $rowPTPP++;
        }

        $footerRow = $this->locator->findFooterRow($sheet, ['TEMPAT PERSETUJUAN']);
        $this->hideEmptyRowsBeforeFooter($sheet, $rowPTPP - 1, $footerRow);
    }

    private function downloadSpreadsheet($spreadsheet, Audit $audit)
    {
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $namaAudit = preg_replace('/[\\\\\/:*?"<>|]/', '', $audit->nama_audit);
        $filename = $namaAudit.'_'.$audit->periode->kode.'.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, ['Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',]);
    }

    private function trimTable(Worksheet $sheet, int $lastDataRow, ?int $footerRow = null): void
    {
        if ($footerRow === null) {
            $highestRow = $sheet->getHighestRow();

            if ($highestRow > $lastDataRow) {
                $sheet->removeRow($lastDataRow + 1, $highestRow - $lastDataRow);
            }

            return;
        }

        $rowsToDelete = $footerRow - ($lastDataRow + 1);

        if ($rowsToDelete > 0) {
            $sheet->removeRow($lastDataRow + 1, $rowsToDelete);
        }

    }

    private function hideEmptyRowsBeforeFooter(Worksheet $sheet, int $lastDataRow, int $footerRow): void
    {
        $spaceBeforeFooter = 1;

        for ($row = $lastDataRow + 1; $row < $footerRow - $spaceBeforeFooter; $row++) {
            $sheet->getRowDimension($row)->setVisible(false);
        }
    }

    private function getDueDate(Audit $audit): string
    {
        $periode = $audit->periode->kode;
        [$tahun, $semester] = explode('-', $periode);
        $tahun = (int) $tahun;
        $semester = (int) $semester;

        if ($semester == '1') {
            $semesterText = 'Genap';
            $tahunAwal = $tahun - 1;
            $tahunAkhir = $tahun;
        } else {
            $semesterText = 'Ganjil';
            $tahunAwal = $tahun;
            $tahunAkhir = $tahun + 1;
        }

        return $periode.' ('.$semesterText.' '.$tahunAwal.'/'.$tahunAkhir.')';
    }

    private function getFormattedAuditDate(Audit $audit): string
    {
        return Carbon::parse($audit->tanggal_audit)->locale('en')->translatedFormat('l, d F Y');
    }

    private function getInstrumenSheet($spreadsheet)
    {
        return $spreadsheet->getSheetByName('Instrumen Audit SPMI 2021');
    }

    private function getHasilSheet($spreadsheet)
    {
        return $spreadsheet->getSheetByName('Hasil AMI  Tindak lanjut');
    }

    private function getPTPPSheet($spreadsheet)
    {
        return $spreadsheet->getSheetByName('PTPP');
    }

}