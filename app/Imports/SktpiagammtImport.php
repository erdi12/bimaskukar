<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sktpiagammt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;

class SktpiagammtImport implements SkipsEmptyRows, ToModel, WithHeadingRow, WithMapping
{
    use Importable;

    /**
     * Map various possible column names to standard keys
     */
    public function map($row): array
    {
        return [
            'nomor_statistik' => $this->findValue($row, ['nomor_statistik', 'no_statistik', 'nustat', 'nomor_stat', 'no_stat', 'nst', 'ns']),
            'nama_majelis' => $this->findValue($row, ['nama_majelis', 'nama', 'majelis', 'nama_mt', 'mt', 'nama_lembaga']),
            'alamat' => $this->findValue($row, ['alamat', 'alamat_lengkap', 'lokasi', 'tempat', 'domisili']),
            'kecamatan' => $this->findValue($row, ['kecamatan_id', 'kecamatan', 'kd_kec', 'kode_kecamatan', 'id_kecamatan', 'kec', 'nama_kecamatan']),
            'kelurahan' => $this->findValue($row, ['kelurahan_id', 'kelurahan', 'kd_kel', 'kode_kelurahan', 'id_kelurahan', 'kel', 'desa', 'nama_kelurahan']),
            'tanggal_berdiri' => $this->findValue($row, ['tanggal_berdiri', 'tgl_berdiri', 'tahun_berdiri', 'thn_berdiri', 'est', 'since', 'berdiri', 'tanggal_berdiri_yyyy_mm_dd']),
            'status' => $this->findValue($row, ['status', 'status_aktif', 'keaktifan', 'state', 'ket_aktif', 'status_aktif_nonaktif', 'status_aktifnonaktif', 'status_aktif_nonaktif_belum_update', 'status_aktifnonaktifbelum_update']),
            'ketua' => $this->findValue($row, ['ketua', 'nama_ketua', 'pimpinan', 'kepala', 'penanggung_jawab', 'pj']),
            'no_hp' => $this->findValue($row, ['no_hp', 'nomor_hp', 'hp', 'ponsel', 'handphone', 'wa', 'whatsapp', 'telp', 'telepon', 'kontak', 'no_telp']),
            'mendaftar' => $this->findValue($row, ['mendaftar', 'tanggal_mendaftar', 'tgl_mendaftar', 'tgl_daftar', 'tanggal_daftar', 'tanggal_masuk', 'tanggal_mendaftar_yyyy_mm_dd']),
            'mendaftar_ulang' => $this->findValue($row, ['mendaftar_ulang', 'tanggal_mendaftar_ulang', 'tgl_mendaftar_ulang', 'tgl_daftar_ulang', 'tanggal_daftar_ulang', 'daftar_ulang', 'tanggal_mendaftar_ulang_yyyy_mm_dd']),
        ];
    }

    /**
     * Helper to find value from multiple possible keys
     */
    private function findValue($row, $aliases)
    {
        foreach ($aliases as $alias) {
            if (array_key_exists($alias, $row)) {
                return $row[$alias];
            }
        }

        return null;
    }

    public function model(array $row)
    {
        // Skip jika semua kolom kosong
        if ($this->isEmptyRow($row)) {
            return null;
        }

        $tanggal_berdiri = ! empty($row['tanggal_berdiri']) ? $this->transformDate($row['tanggal_berdiri']) : null;
        $mendaftar = ! empty($row['mendaftar']) ? $this->transformDate($row['mendaftar']) : null;
        $mendaftar_ulang = ! empty($row['mendaftar_ulang']) ? $this->transformDate($row['mendaftar_ulang']) : null;

        // Fallback Logic:
        // Jika mendaftar kosong, gunakan tanggal_berdiri, jika masih kosong gunakan sekarang
        if (! $mendaftar) {
            $mendaftar = $tanggal_berdiri ?: Carbon::now()->format('Y-m-d');
        }

        // Jika tanggal_berdiri kosong, samakan dengan mendaftar
        if (! $tanggal_berdiri) {
            $tanggal_berdiri = $mendaftar;
        }

        // Jika mendaftar_ulang kosong, gunakan nilai mendaftar (default behavior) atau sekarang
        if (! $mendaftar_ulang) {
            // Opsional: mendaftar_ulang biasanya sama dengan mendaftar jika baru,
            // atau ditambah 5 tahun. Kita pakai tanggal mendaftar saja sebagai default aman.
            $mendaftar_ulang = $mendaftar;
        }

        // Normalisasi status di sini juga (untuk simpanan di DB)
        $statusRaw = isset($row['status']) ? $row['status'] : null;
        $status = $this->normalizeStatus($statusRaw);

        // 1. Lookup Kecamatan
        $kecamatanNama = $row['kecamatan'] ?? null;
        $kecamatan = null;
        if ($kecamatanNama) {
            $kecamatan = Kecamatan::where('kecamatan', 'LIKE', '%'.$kecamatanNama.'%')->first();
        }

        if (! $kecamatan) {
            Log::warning('Kecamatan not found for: '.$kecamatanNama);

            return null;
        }

        // 2. Lookup Kelurahan
        $kelurahanNama = $row['kelurahan'] ?? null;
        $kelurahan = null;
        if ($kelurahanNama) {
            $kelurahan = Kelurahan::where('nama_kelurahan', 'LIKE', '%'.$kelurahanNama.'%')->first();
        }

        if (! $kelurahan) {
            Log::warning('Kelurahan not found for: '.$kelurahanNama);

            return null;
        }

        // Check Update or Create
        $nomorStatistik = $row['nomor_statistik'] ?? null;

        if ($nomorStatistik) {
            $exists = Sktpiagammt::where('nomor_statistik', $nomorStatistik)->first();
            if ($exists) {
                $exists->update([
                    'nama_majelis' => $row['nama_majelis'] ?? $exists->nama_majelis,
                    'alamat' => $row['alamat'] ?? $exists->alamat,
                    'kecamatan_id' => $kecamatan->id,
                    'kelurahan_id' => $kelurahan->id,
                    'tanggal_berdiri' => $tanggal_berdiri ?? $exists->tanggal_berdiri,
                    'status' => $status ?? $exists->status,
                    'ketua' => $row['ketua'] ?? $exists->ketua,
                    'no_hp' => $row['no_hp'] ?? $exists->no_hp,
                    'mendaftar' => $mendaftar ?? $exists->mendaftar,
                    'mendaftar_ulang' => $mendaftar_ulang ?? $exists->mendaftar_ulang,
                ]);

                return $exists;
            }
        }

        return new Sktpiagammt([
            'nomor_statistik' => $nomorStatistik,
            'nama_majelis' => $row['nama_majelis'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'kecamatan_id' => $kecamatan->id,
            'kelurahan_id' => $kelurahan->id,
            'tanggal_berdiri' => $tanggal_berdiri,
            'status' => $status,
            'ketua' => $row['ketua'] ?? null,
            'no_hp' => $row['no_hp'] ?? null,
            'mendaftar' => $mendaftar,
            'mendaftar_ulang' => $mendaftar_ulang,
        ]);
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
        if (is_null($value)) {
            return null;
        }

        $s = strtolower(trim((string) $value));
        // hilangkan spasi dan tanda hubung
        $s = str_replace(['-', ' '], '', $s);

        // map common representations
        if (in_array($s, ['aktif', '1', 'ya', 'yes', 'true'])) {
            return 'aktif';
        }
        if (in_array($s, ['nonaktif', 'non', '0', 'tidak', 'no', 'false', 'nonaktif'])) {
            return 'nonaktif';
        }

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
            'status', 'ketua', 'no_hp', 'mendaftar', 'mendaftar_ulang',
        ];

        // Periksa apakah semua kolom wajib kosong
        foreach ($requiredColumns as $column) {
            if (! empty($row[$column])) {
                return false;
            }
        }

        return true;
    }
}
