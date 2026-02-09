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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MarbotExport extends DefaultValueBinder implements FromQuery, ShouldAutoSize, WithColumnFormatting, WithCustomValueBinder, WithHeadings, WithMapping, WithStyles
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
            $namaRumahIbadah,
            $idRumahIbadah,
            $marbot->kecamatan ? strtoupper($marbot->kecamatan->kecamatan) : '-',
            $marbot->kelurahan ? strtoupper($marbot->kelurahan->nama_kelurahan) : '-',
            strtoupper($marbot->nama_lengkap),
            $marbot->nik, // Force string for NIK
            $marbot->nomor_rekening,
            $marbot->npwp,
            strtoupper($marbot->tempat_lahir.', '.($marbot->tanggal_lahir ? Carbon::parse($marbot->tanggal_lahir)->locale('id')->isoFormat('D MMMM Y') : '-')),
            $usia,
            $masaKerja,
            $marbot->no_hp,
            strtoupper($marbot->alamat),
            $marbot->tipe_rumah_ibadah,
            $marbot->tanggal_mulai_bekerja ? Carbon::parse($marbot->tanggal_mulai_bekerja)->format('d-m-Y') : '-',
            strtoupper($marbot->status),
            $marbot->nomor_induk_marbot,
            $marbot->created_at->format('d-m-Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Rumah Ibadah',
            'ID Rumah Ibadah',
            'Kecamatan',
            'Kelurahan',
            'Nama Lengkap',
            'NIK',
            'Nomor Rekening',
            'NPWP',
            'Tempat, Tanggal Lahir',
            'Usia',
            'Masa Kerja',
            'No HP',
            'Alamat',
            'Tipe Rumah Ibadah',
            'Tanggal Mulai Bekerja',
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

    public function bindValue(Cell $cell, $value)
    {
        // Force Text format for specific columns to prevent scientific notation (E+15)
        // B=NIK, E=No HP, F=NPWP, K=ID Rumah Ibadah, P=Nomor Rekening, R=NIM
        if (in_array($cell->getColumn(), ['C', 'G', 'H', 'I', 'M', 'R'])) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // Nomor Rekening
            'G' => NumberFormat::FORMAT_TEXT, // NIK
            'H' => NumberFormat::FORMAT_TEXT, // No HP
            'I' => NumberFormat::FORMAT_TEXT, // NPWP
            'M' => NumberFormat::FORMAT_TEXT, // ID Rumah Ibadah
            'R' => NumberFormat::FORMAT_TEXT, // NIM
        ];
    }
}
