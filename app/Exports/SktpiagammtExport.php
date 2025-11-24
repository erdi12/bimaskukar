<?php

namespace App\Exports;

use App\Models\Sktpiagammt;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SktpiagammtExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Sktpiagammt::with(['kecamatan', 'kelurahan'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nomor_statistik',
            'nama_majelis',
            'alamat',
            'kelurahan',
            'kecamatan',
            'tanggal_berdiri',
            'status',
            'ketua',
            'no_hp',
            'mendaftar',
            'mendaftar_ulang'
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->nomor_statistik,
            $row->nama_majelis,
            $row->alamat,
            $row->kelurahan 
                ? ucwords(strtolower($row->kelurahan->nama_kelurahan)) 
                : '',
            $row->kecamatan 
                ? ucwords(strtolower($row->kecamatan->kecamatan)) 
                : '',
            Carbon::parse($row->tanggal_berdiri)->locale('id')->format('j F Y'),
            $row->status,
            $row->ketua,
            $row->no_hp,
            Carbon::parse($row->mendaftar)->locale('id')->format('j F Y'),
            Carbon::parse($row->mendaftar_ulang)->locale('id')->format('j F Y')
        ];
    }
}