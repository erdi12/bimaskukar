<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MarbotTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new MarbotInputSheet(),
            new MarbotReferenceSheet(),
        ];
    }
}
