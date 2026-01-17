<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MarbotImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            // Select the first sheet (index 0) to process using MarbotDataImport
            0 => new MarbotDataImport(),
        ];
    }
}
