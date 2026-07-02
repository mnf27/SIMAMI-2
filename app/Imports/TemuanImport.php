<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemuanImport implements WithMultipleSheets
{
    protected $auditId;
    protected $unitId;

    public function __construct($auditId, $unitId)
    {
        $this->auditId = $auditId;
        $this->unitId = $unitId;
    }

    public function sheets(): array
    {
        return [
            0 => new InstrumenSheetImport(
                $this->auditId,
                $this->unitId
            ),
        ];
    }
}
