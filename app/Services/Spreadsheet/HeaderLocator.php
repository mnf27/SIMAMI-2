<?php

namespace App\Services\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class HeaderLocator
{
    private function contains(string $header, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (str_contains($header, strtoupper($keyword))) {
                return true;
            }
        }

        return false;
    }

    public function findHeaderRow(Worksheet $sheet, array $requiredHeaders): ?int
    {
        $highestRow = $sheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; $row++) {
            $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());
            $headers = [];

            for ($col = 1; $col <= $highestColumn; $col++) {
                $headers[] = strtoupper(trim((string) $sheet->getCellByColumnAndRow($col, $row)
                    ->getCalculatedValue()));
            }

            $found = true;

            foreach ($requiredHeaders as $header) {
                if (! in_array(strtoupper($header), $headers)) {
                    $found = false;
                    break;
                }
            }

            if ($found) {
                return $row;
            }
        }

        return null;
    }

    public function findInstrumenColumns(Worksheet $sheet, int $headerRow): array
    {
        $mapping = [];
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($col = 1; $col <= $highestColumn; $col++) {
            $header = strtoupper(preg_replace('/\s+/', ' ', trim((string) $sheet->getCellByColumnAndRow($col,
                $headerRow)->getCalculatedValue())));

            if ($this->contains($header, ['KODE', 'STANDAR', 'INDIKATOR'])) {
                $mapping['kode'] = $col;
            }

            if ($this->contains($header, ['TEMUAN'])) {
                $mapping['temuan'] = $col;
            }

            if ($this->contains($header, ['HASIL AMI'])) {
                $mapping['hasil_ami'] = $col;
            }

            if (
                $header === 'TINDAKAN PERBAIKAN'
                || str_starts_with($header, 'TINDAKAN PERBAIKAN (')
            ) {
                $mapping['tindakan'] = $col;
            }

            if ($this->contains($header, ['REFERENSI', 'BUKTI'])) {
                $mapping['bukti'] = $col;
            }

            if (str_contains($header, 'TANGGAPAN AUDITOR')) {

                if (! isset($mapping['auditor1'])) {
                    $mapping['auditor1'] = $col;
                } else {
                    $mapping['auditor2'] = $col;
                }
            }

            if ($this->contains($header, ['VALIDASI', 'STATUS'])) {
                $mapping['status'] = $col;
            }
        }

        $mapping['start'] = 1;
        $mapping['end'] = $highestColumn;

        return $mapping;
    }

    public function findTableRange(Worksheet $sheet, int $headerRow): array
    {
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());
        $start = null;

        for ($col = 1; $col <= $highestColumn; $col++) {
            $value = strtoupper(preg_replace('/\s+/', ' ', trim((string) $sheet->getCellByColumnAndRow($col,
                $headerRow)->getCalculatedValue())));

            if ($value !== '') {
                $start = $col;
                break;
            }
        }

        if ($start === null) {
            throw new \Exception('Kolom awal tabel tidak ditemukan.');
        }

        return ['start' => $start, 'end' => $highestColumn,];
    }

    public function findFooterRow(Worksheet $sheet, array $keywords): ?int
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumn; $col++) {
                $text = strtoupper(trim((string) $sheet->getCellByColumnAndRow($col, $row)
                    ->getCalculatedValue()));

                foreach ($keywords as $keyword) {
                    if (str_contains($text, strtoupper($keyword))) {
                        return $row;
                    }
                }
            }
        }

        return null;
    }

    public function findPTPPColumns(Worksheet $sheet, int $headerRow): array
    {
        $mapping = [];
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($col = 1; $col <= $highestColumn; $col++) {
            $header = strtoupper(preg_replace('/\s+/', '', trim((string) $sheet->getCellByColumnAndRow($col,
                $headerRow)->getCalculatedValue())));

            switch ($header) {
                case 'STANDAR':
                    $mapping['kode'] = $col;
                    break;
                case 'INDIKATOR':
                    $mapping['indikator'] = $col;
                    break;
                case 'HASILAUDIT':
                    $mapping['hasil'] = $col;
                    break;
                case 'AKARMASALAH':
                    $mapping['akar'] = $col;
                    break;
                case 'AKIBAT':
                    $mapping['akibat'] = $col;
                    break;
                case 'KETERCAPAIANSTANDAR':
                    $mapping['ketercapaian'] = $col;
                    break;
                case 'TANGGAPANAUDITI':
                    $mapping['tanggapan'] = $col;
                    break;
                case 'RENCANAPERBAIKAN':
                    $mapping['rencana'] = $col;
                    break;
                case 'JADWALPERBAIKAN':
                    $mapping['jadwal'] = $col;
                    break;
                case 'PENANGGUNGJAWAB':
                    $mapping['pj'] = $col;
                    break;
                case 'RENCANAPENCEGAHAN':
                    $mapping['pencegahan'] = $col;
                    break;
                case 'JADWALPENCEGAHAN':
                    $mapping['jadwal_pencegahan'] = $col;
                    break;
                case 'KONDISI':
                    $mapping['kondisi'] = $col;
                    break;
            }
        }

        return $mapping;
    }

    public function findHasilAMIColumns(Worksheet $sheet, int $headerRow): array
    {
        $mapping = [];
        $highestColumn = Coordinate::columnIndexFromString($sheet->getHighestColumn());

        for ($col = 1; $col <= $highestColumn; $col++) {
            $header = strtoupper(preg_replace('/\s+/', ' ', trim((string) $sheet->getCellByColumnAndRow($col,
                $headerRow)->getCalculatedValue())));

            if ($this->contains($header, ['NO'])) {
                $mapping['kode'] = $col;
            }

            if ($this->contains($header, ['TEMUAN'])) {
                $mapping['temuan'] = $col;
            }

            if ($this->contains($header, ['PENYEBAB'])) {
                $mapping['akar'] = $col;
            }

            if ($this->contains($header, ['TINDAKAN PERBAIKAN'])) {
                $mapping['perbaikan'] = $col;
            }

            if ($this->contains($header, ['TINDAKAN PENCEGAHAN'])) {
                $mapping['pencegahan'] = $col;
            }

            if ($this->contains($header, ['DUE DATE'])) {
                $mapping['duedate'] = $col;
            }

            if ($this->contains($header, ['STATUS'])) {
                $mapping['status'] = $col;
            }
        }

        return $mapping;
    }
}