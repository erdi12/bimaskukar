<?php

namespace App\Exports;

use App\Models\Sktpiagammt;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SktpiagammtExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    /**
     * @param string|null $startDate
     * @param string|null $endDate
     */
    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Sktpiagammt::with(['kecamatan', 'kelurahan']);

        if ($this->startDate) {
            $query->whereDate('mendaftar', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('mendaftar', '<=', $this->endDate);
        }

        return $query->orderBy('mendaftar', 'desc')->get();
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
            'jumlah_anggota',
            'materi',
            'mendaftar',
            'mendaftar_ulang',
            'diposting_pada'
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        // Format alamat lengkap
        $alamatLengkap = $row->alamat;
        if ($row->kelurahan) {
            $jenis = $row->kelurahan->jenis_kelurahan == 'Desa' ? 'Desa' : 'Kel.';
            $alamatLengkap .= ', ' . $jenis . ' ' . ucwords(strtolower($row->kelurahan->nama_kelurahan));
        }
        if ($row->kecamatan) {
            $alamatLengkap .= ', Kec. ' . ucwords(strtolower($row->kecamatan->kecamatan));
        }

        return [
            $row->nomor_statistik,
            $row->nama_majelis,
            $alamatLengkap,
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
            $row->jumlah_anggota,
            $row->materi,
            Carbon::parse($row->mendaftar)->locale('id')->format('j F Y'),
            Carbon::parse($row->mendaftar_ulang)->locale('id')->format('j F Y'),
            $row->created_at->format('d-m-Y H:i:s')
        ];
    }
}