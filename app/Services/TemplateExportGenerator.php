<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Services\Spreadsheet\HeaderLocator;

class TemplateExportGenerator
{
    protected HeaderLocator $locator;

    public function __construct(HeaderLocator $locator)
    {
        $this->locator = $locator;
    }

    public function generate(string $sourcePath, string $targetPath): void
    {
        $spreadsheet = IOFactory::load($sourcePath);
        $this->clearInstrumenSheet($spreadsheet->getSheet(0));
        $this->clearTindakLanjutSheet($spreadsheet->getSheet(1));
        $this->clearPTPPSheet($spreadsheet->getSheet(2));
        $directory = dirname($targetPath);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $spreadsheet->setActiveSheetIndex(0);
        $writer->setPreCalculateFormulas(true);
        $writer->save($targetPath);
    }

    private function clearInstrumenSheet(Worksheet $sheet): void
    {
        $headerRow = $this->locator->findHeaderRow($sheet, ['KODE STANDAR',]);

        if ($headerRow === null) {
            throw new \Exception('Header Sheet Instrumen tidak ditemukan.');
        }

        $columns = $this->locator->findTableRange($sheet, $headerRow);
        $this->clearSheetData($sheet, $headerRow + 1, $columns);
    }

    private function clearTindakLanjutSheet(Worksheet $sheet): void
    {
        $headerRow = $this->locator->findHeaderRow($sheet, ['NO.', 'TEMUAN', 'PENYEBAB KETIDAK SESUAIAN',
            'TINDAKAN PERBAIKAN',]);

        if ($headerRow === null) {
            throw new \Exception('Header Sheet Hasil AMI tidak ditemukan.');
        }

        $columns = $this->locator->findTableRange($sheet, $headerRow);
        $footerRow = $this->locator->findFooterRow($sheet, ['PENANGGUNGJAWAB', 'AUDITOR']);

        $this->clearSheetData($sheet, $headerRow + 2, $columns, $footerRow);
    }

    private function clearPTPPSheet(Worksheet $sheet): void
    {
        $headerRow = $this->locator->findHeaderRow($sheet, ['STANDAR', 'INDIKATOR',]);

        if ($headerRow === null) {
            throw new \Exception('Header Sheet PTPP tidak ditemukan.');
        }

        $columns = $this->locator->findTableRange($sheet, $headerRow);
        $footerRow = $this->locator->findFooterRow($sheet, ['TEMPAT PERSETUJUAN',]);
        $this->clearSheetData($sheet, $headerRow + 1, $columns, $footerRow);
    }

    private function clearSheetData(Worksheet $sheet, int $firstDataRow, array $columns,
        ?int $lastRow = null): void
    {
        $highestRow = $lastRow ?? $sheet->getHighestRow();

        for ($row = $firstDataRow; $row < $highestRow; $row++) {
            $this->clearRow($sheet, $row, $columns);
        }
    }

    private function clearRow(Worksheet $sheet, int $row, array $columns): void
    {
        for ($col = $columns['start']; $col <= $columns['end']; $col++) {
            $cell = $sheet->getCellByColumnAndRow($col, $row);

            if ($cell->isFormula()) {
                continue;
            }

            $cell->setValue('');
        }
    }
}