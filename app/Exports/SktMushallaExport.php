<?php

namespace App\Exports;

use App\Models\SktMushalla;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SktMushallaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SktMushalla::with(['kecamatan', 'kelurahan', 'tipologiMushalla'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Mushalla',
            'Nomor ID Mushalla',
            'Alamat',
            'Kecamatan',
            'Kelurahan/Desa',
            'Tipologi',
            'Created At',
            'Updated At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->nama_mushalla,
            $row->nomor_id_mushalla,
            $row->alamat_mushalla,
            $row->kecamatan->kecamatan ?? '',
            $row->kelurahan->nama_kelurahan ?? '',
            $row->tipologiMushalla->nama_tipologi ?? '',
            $row->created_at,
            $row->updated_at,
        ];
    }
}
