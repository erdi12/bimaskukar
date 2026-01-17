<?php

namespace App\Exports;

use App\Models\Marbot;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MarbotExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;

    protected $endDate;

    protected $status;

    private $rowNumber = 0;

    public function __construct($startDate, $endDate, $status = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
        $this->status = $status;
    }

    public function query()
    {
        $query = Marbot::with(['kecamatan', 'kelurahan', 'masjid', 'mushalla']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        if ($this->status && $this->status !== 'semua') {
            $query->where('status', $this->status);
        }

        return $query->latest();
    }

    public function map($marbot): array
    {
        $this->rowNumber++;

        // Calculate Usia
        $usia = $marbot->tanggal_lahir ? Carbon::parse($marbot->tanggal_lahir)->age.' Tahun' : '-';

        // Calculate Masa Kerja
        $masaKerja = '-';
        if ($marbot->tanggal_mulai_bekerja) {
            $start = Carbon::parse($marbot->tanggal_mulai_bekerja);
            $diff = $start->diff(Carbon::now());
            $masaKerja = $diff->y.' Tahun '.$diff->m.' Bulan';
        }

        // Get Rumah Ibadah Details
        $namaRumahIbadah = '-';
        $idRumahIbadah = '-';

        if ($marbot->tipe_rumah_ibadah == 'Masjid' && $marbot->masjid) {
            $namaRumahIbadah = $marbot->masjid->nama_masjid;
            $idRumahIbadah = $marbot->masjid->nomor_id_masjid;
        } elseif ($marbot->tipe_rumah_ibadah == 'Mushalla' && $marbot->mushalla) {
            $namaRumahIbadah = $marbot->mushalla->nama_mushalla;
            $idRumahIbadah = $marbot->mushalla->nomor_id_mushalla;
        }

        return [
            $this->rowNumber,
            "'".$marbot->nik, // Force string for NIK
            $marbot->nama_lengkap,
            $marbot->tempat_lahir,
            $marbot->tanggal_lahir ? Carbon::parse($marbot->tanggal_lahir)->format('d-m-Y') : '-',
            "'".$marbot->no_hp,
            $marbot->alamat,
            $marbot->kecamatan ? $marbot->kecamatan->kecamatan : '-',
            $marbot->kelurahan ? $marbot->kelurahan->kelurahan : '-',
            $marbot->tipe_rumah_ibadah,
            $idRumahIbadah !== '-' ? "'".$idRumahIbadah : $idRumahIbadah,
            $namaRumahIbadah,
            $marbot->tanggal_mulai_bekerja ? Carbon::parse($marbot->tanggal_mulai_bekerja)->format('d-m-Y') : '-',
            $masaKerja,
            $usia,
            "'".$marbot->nomor_rekening,
            ucfirst($marbot->status),
            "'".$marbot->nomor_induk_marbot,
            $marbot->created_at->format('d-m-Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Lengkap',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No HP',
            'Alamat',
            'Kecamatan',
            'Kelurahan',
            'Tipe Rumah Ibadah',
            'ID Rumah Ibadah',
            'Nama Rumah Ibadah',
            'Tanggal Mulai Bekerja',
            'Masa Kerja',
            'Usia',
            'Nomor Rekening',
            'Status',
            'Nomor Induk Marbot (NIM)',
            'Tanggal Daftar',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
