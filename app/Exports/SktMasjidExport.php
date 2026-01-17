<?php

namespace App\Exports;

use App\Models\SktMasjid;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SktMasjidExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Masjid',
            'Nomor ID Masjid',
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
            $row->nama_masjid,
            $row->nomor_id_masjid,
            $row->alamat_masjid,
            $row->kecamatan->kecamatan ?? '',
            $row->kelurahan->nama_kelurahan ?? '',
            $row->tipologiMasjid->nama_tipologi ?? '',
            $row->created_at,
            $row->updated_at,
        ];
    }
}
