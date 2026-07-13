<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemuanImport implements WithMultipleSheets
{
    protected $auditId;

    public function __construct($auditId)
    {
        $this->auditId = $auditId;
    }

    public function sheets(): array
    {
        return [
            0 => new InstrumenSheetImport(
                $this->auditId,
            ),
        ];
    }
}
