<?php

namespace App\Exports;

use App\Models\SktMasjid;
use App\Models\SktMushalla;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MarbotReferenceSheet implements FromCollection, ShouldAutoSize, WithHeadings, WithTitle
{
    public function collection()
    {
        $masjids = SktMasjid::select('id', 'nomor_id_masjid', 'nama_masjid as nama', 'alamat_masjid as alamat')->get()->map(function ($item) {
            return [
                'Tipe' => 'Masjid',
                'No. ID Rumah Ibadah (Copy Ini)' => $item->nomor_id_masjid,
                'Nama' => $item->nama,
                'Alamat' => $item->alamat,
            ];
        });

        $mushallas = SktMushalla::select('id', 'nomor_id_mushalla', 'nama_mushalla as nama', 'alamat_mushalla as alamat')->get()->map(function ($item) {
            return [
                'Tipe' => 'Mushalla',
                'No. ID Rumah Ibadah (Copy Ini)' => $item->nomor_id_mushalla,
                'Nama' => $item->nama,
                'Alamat' => $item->alamat,
            ];
        });

        return $masjids->merge($mushallas);
    }

    public function headings(): array
    {
        return [
            'Tipe',
            'No. ID Rumah Ibadah (Copy Ini)',
            'Nama Rumah Ibadah',
            'Alamat',
        ];
    }

    public function title(): string
    {
        return 'Data Referensi ID';
    }
}
