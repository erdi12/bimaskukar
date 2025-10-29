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
            'Nomor Statistik',
            'Nama Majelis',
            'Alamat',
            'Kelurahan',
            'Kecamatan',
            'Tanggal Berdiri',
            'Status',
            'Ketua',
            'No HP',
            'Tanggal Mendaftar',
            'Tanggal Mendaftar Ulang'
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
            $row->kelurahan ? $row->kelurahan->nama_kelurahan : '',
            $row->kecamatan ? ucwords($row->kecamatan->kecamatan) : '',
            $row->tanggal_berdiri,
            $row->status,
            $row->ketua,
            $row->no_hp,
            Carbon::parse($row->mendaftar)->locale('id')->format('j F Y'),
            Carbon::parse($row->mendaftar_ulang)->locale('id')->format('j F Y')
        ];
    }
}