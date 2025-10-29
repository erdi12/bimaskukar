<?php

namespace App\Imports;

use App\Models\Sktpiagammt;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SktpiagammtImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;

    public function model(array $row)
    {
        // Skip jika semua kolom kosong
        if ($this->isEmptyRow($row)) {
            return null;
        }

        $tanggal_berdiri  = !empty($row['tanggal_berdiri']) ? $this->transformDate($row['tanggal_berdiri']) : null;
        $mendaftar        = !empty($row['mendaftar']) ? $this->transformDate($row['mendaftar']) : null;
        $mendaftar_ulang  = !empty($row['mendaftar_ulang']) ? $this->transformDate($row['mendaftar_ulang']) : null;

        // Normalisasi status di sini juga (untuk simpanan di DB)
        $statusRaw = isset($row['status']) ? $row['status'] : null;
        $status = $this->normalizeStatus($statusRaw);

        return new Sktpiagammt([
            'nomor_statistik' => $row['nomor_statistik'] ?? null,
            'nama_majelis'    => $row['nama_majelis'] ?? null,
            'alamat'          => $row['alamat'] ?? null,
            'kecamatan_id'    => $row['kecamatan_id'] ?? null,
            'kelurahan_id'    => $row['kelurahan_id'] ?? null,
            'tanggal_berdiri' => $tanggal_berdiri,
            'status'          => $status,
            'ketua'           => $row['ketua'] ?? null,
            'no_hp'           => $row['no_hp'] ?? null,
            'mendaftar'       => $mendaftar,
            'mendaftar_ulang' => $mendaftar_ulang,
        ]);
    }

    public function rules(): array
    {
        return [
            'nomor_statistik' => 'required|unique:sktpiagammts,nomor_statistik',
            'nama_majelis'    => 'required',
            'alamat'          => 'required',
            'kecamatan_id'    => 'required|exists:kecamatans,id',
            'kelurahan_id'    => 'required|exists:kelurahans,id',

            // Gunakan closure agar kita bisa menerima excel serial number atau string tanggal
            'tanggal_berdiri' => [
                'required',
                function($attribute, $value, $fail) {
                    if (!$this->isValidExcelDateOrParsable($value)) {
                        $fail('Format '.$attribute.' tidak valid.');
                    }
                }
            ],

            'status' => [
                'required',
                function($attribute, $value, $fail) {
                    $normalized = $this->normalizeStatus($value);
                    if (!in_array($normalized, ['aktif','nonaktif'])) {
                        $fail('Status harus aktif atau nonaktif.');
                    }
                }
            ],

            'ketua'           => 'required',
            'no_hp'           => 'required',

            'mendaftar' => [
                'required',
                function($attribute, $value, $fail) {
                    if (!$this->isValidExcelDateOrParsable($value)) {
                        $fail('Format '.$attribute.' tidak valid.');
                    }
                }
            ],

            'mendaftar_ulang' => [
                'required',
                function($attribute, $value, $fail) {
                    if (!$this->isValidExcelDateOrParsable($value)) {
                        $fail('Format '.$attribute.' tidak valid.');
                    }
                }
            ],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nomor_statistik.required' => 'Nomor statistik harus diisi',
            'nomor_statistik.unique' => 'Nomor statistik sudah terdaftar di database. Silakan gunakan nomor statistik yang lain',
            'nama_majelis.required' => 'Nama majelis harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'kecamatan_id.required' => 'Kecamatan harus diisi',
            'kecamatan_id.exists' => 'Kecamatan tidak valid',
            'kelurahan_id.required' => 'Kelurahan harus diisi',
            'kelurahan_id.exists' => 'Kelurahan tidak valid',
            'tanggal_berdiri.required' => 'Tanggal berdiri harus diisi',
            'status.required' => 'Status harus diisi',
            'ketua.required' => 'Nama ketua harus diisi',
            'no_hp.required' => 'Nomor HP harus diisi',
            'mendaftar.required' => 'Tanggal mendaftar harus diisi',
            'mendaftar_ulang.required' => 'Tanggal mendaftar ulang harus diisi',
        ];
    }

    /**
     * Transform date value from excel to proper Y-m-d string or null
     */
    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                // Excel serial number -> DateTime -> format Y-m-d
                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                )->format('Y-m-d');
            }

            // Jika sudah string, parse dengan Carbon lalu format
            return Carbon::parse(trim($value))->format('Y-m-d');
        } catch (\Exception $e) {
            // Jika gagal parse, kembalikan null supaya tidak menyebabkan error saat insert ke DB
            return null;
        }
    }

    /**
     * Cek apakah nilai bisa diterima sebagai tanggal (excel serial number atau parsable)
     */
    private function isValidExcelDateOrParsable($value): bool
    {
        if (is_numeric($value)) {
            try {
                \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        try {
            Carbon::parse(trim($value));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Normalisasi status agar berbagai variasi diterima
     */
    private function normalizeStatus($value)
    {
        if (is_null($value)) return null;

        $s = strtolower(trim((string)$value));
        // hilangkan spasi dan tanda hubung
        $s = str_replace(['-', ' '], '', $s);

        // map common representations
        if (in_array($s, ['aktif','1','ya','yes','true'])) return 'aktif';
        if (in_array($s, ['nonaktif','non','0','tidak','no','false','nonaktif'])) return 'nonaktif';

        // fallback: kembalikan original setelah trim/lower
        return $s;
    }

    /**
     * Check if row is completely empty
     */
    private function isEmptyRow($row): bool
    {
        // Daftar kolom yang wajib diisi
        $requiredColumns = [
            'nomor_statistik', 'nama_majelis', 'alamat', 
            'kecamatan_id', 'kelurahan_id', 'tanggal_berdiri',
            'status', 'ketua', 'no_hp', 'mendaftar', 'mendaftar_ulang'
        ];

        // Periksa apakah semua kolom wajib kosong
        foreach ($requiredColumns as $column) {
            if (!empty($row[$column])) {
                return false;
            }
        }

        return true;
    }
}
