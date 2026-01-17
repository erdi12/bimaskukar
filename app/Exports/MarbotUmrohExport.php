<?php

namespace App\Exports;

use App\Models\Marbot;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MarbotUmrohExport extends DefaultValueBinder implements FromQuery, ShouldAutoSize, WithColumnFormatting, WithCustomValueBinder, WithHeadings, WithMapping, WithStyles
{
    private $rowNumber = 0;

    protected $tahun;

    public function __construct($tahun = null)
    {
        $this->tahun = $tahun;
    }

    public function bindValue(Cell $cell, $value)
    {
        if (in_array($cell->getColumn(), ['B', 'F', 'M'])) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function query()
    {
        $query = Marbot::query()
            ->with(['kecamatan', 'kelurahan', 'masjid', 'mushalla'])
            ->where('status_umroh', 'berangkat');

        if ($this->tahun) {
            $query->where('tahun_umroh', $this->tahun);
        }

        // Order by year then month
        return $query->orderBy('tahun_umroh', 'desc')->orderBy('bulan_umroh', 'asc');
    }

    public function map($marbot): array
    {
        $this->rowNumber++;

        // Calculate Usia
        $usia = $marbot->tanggal_lahir ? Carbon::parse($marbot->tanggal_lahir)->age.' Tahun' : '-';

        // Get Rumah Ibadah Details
        $namaRumahIbadah = '-';

        if ($marbot->tipe_rumah_ibadah == 'Masjid' && $marbot->masjid) {
            $namaRumahIbadah = $marbot->masjid->nama_masjid;
        } elseif ($marbot->tipe_rumah_ibadah == 'Mushalla' && $marbot->mushalla) {
            $namaRumahIbadah = $marbot->mushalla->nama_mushalla;
        }

        // Format Jadwal
        $bulanNama = $marbot->bulan_umroh ? Carbon::create(null, (int) $marbot->bulan_umroh, 1)->locale('id')->monthName : '-';

        return [
            $this->rowNumber,
            $marbot->nik,
            $marbot->nama_lengkap,
            $marbot->tempat_lahir.', '.($marbot->tanggal_lahir ? Carbon::parse($marbot->tanggal_lahir)->format('d-m-Y') : ''),
            $usia,
            $marbot->no_hp,
            $marbot->alamat,
            $marbot->kecamatan ? $marbot->kecamatan->kecamatan : '-',
            $marbot->kelurahan ? $marbot->kelurahan->kelurahan : '-',
            $namaRumahIbadah,
            $marbot->bulan_umroh ? $bulanNama.' '.$marbot->tahun_umroh : '-',
            $marbot->nomor_induk_marbot,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => '@', // NIK
            'F' => '@', // No HP
            'M' => '@', // NIM
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Lengkap',
            'TTL',
            'Usia',
            'No HP',
            'Alamat Domisili',
            'Kecamatan',
            'Kelurahan',
            'Asal Masjid/Mushalla',
            'Bulan & Tahun Keberangkatan',
            'NIM',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
