<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Marbot;
use App\Models\SktMasjid;
use App\Models\SktMushalla;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MarbotDataImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // 1. Cari ID Kecamatan berdasarkan Nama
        $kecamatan = Kecamatan::where('kecamatan', 'like', '%'.trim($row['nama_kecamatan']).'%')->first();

        // 2. Cari ID Kelurahan berdasarkan Nama
        $kelurahan = Kelurahan::where('nama_kelurahan', 'like', '%'.trim($row['nama_kelurahan']).'%')->first();

        // Fallback jika tidak ditemukan (bisa null atau skip)
        $kecamatanId = $kecamatan ? $kecamatan->id : null;
        $kelurahanId = $kelurahan ? $kelurahan->id : null;

        // 3. Logika Pencarian ID Rumah Ibadah (Prioritas: No ID -> Nama)
        $realRumahIbadahId = null;
        $noIdRumahIbadahInput = trim($row['no_id_rumah_ibadah'] ?? $row['id_rumah_ibadah'] ?? '');

        if (! empty($noIdRumahIbadahInput)) {
            // A. Cari berdasarkan NOMOR ID (Pasti Unik)
            if ($row['tipe_rumah_ibadah'] == 'Masjid') {
                $masjid = SktMasjid::where('nomor_id_masjid', $noIdRumahIbadahInput)->first();
                $realRumahIbadahId = $masjid ? $masjid->id : null;
            } elseif ($row['tipe_rumah_ibadah'] == 'Mushalla') {
                $mushalla = SktMushalla::where('nomor_id_mushalla', $noIdRumahIbadahInput)->first();
                $realRumahIbadahId = $mushalla ? $mushalla->id : null;
            }
        }

        // B. Search by Name (Fallback)
        $namaInput = $row['nama_rumah_ibadah'] ?? $row['id_rumah_ibadah'] ?? '';
        if (empty($realRumahIbadahId) && ! empty($namaInput)) {
            if ($row['tipe_rumah_ibadah'] == 'Masjid') {
                $masjid = SktMasjid::where('nama_masjid', 'like', '%'.$namaInput.'%')->first();
                $realRumahIbadahId = $masjid ? $masjid->id : null;
            } elseif ($row['tipe_rumah_ibadah'] == 'Mushalla') {
                $mushalla = SktMushalla::where('nama_mushalla', 'like', '%'.$namaInput.'%')->first();
                $realRumahIbadahId = $mushalla ? $mushalla->id : null;
            }
        }

        return new Marbot([
            'nik' => $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $row['tanggal_lahir'],
            'no_hp' => $row['no_hp'] ?? '-',
            'alamat' => $row['alamat_domisili'] ?? '-',
            'kecamatan_id' => $kecamatanId,
            'kelurahan_id' => $kelurahanId,
            'tipe_rumah_ibadah' => $row['tipe_rumah_ibadah'],
            'rumah_ibadah_id' => $realRumahIbadahId,
            'nomor_rekening' => $row['nomor_rekening'],
            'npwp' => $row['npwp'],
            'status' => 'perbaikan',
            'catatan' => 'Data migrasi via Excel. Harap lengkapi dokumen scan KTP, KK, dan SK.',
        ]);
    }

    public function rules(): array
    {
        return [
            'nik' => 'required|unique:marbots,nik',
            'nama_lengkap' => 'required',
            'nama_kecamatan' => 'required',
            'tipe_rumah_ibadah' => 'required|in:Masjid,Mushalla',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nik.unique' => 'NIK :input sudah terdaftar.',
            'nik.required' => 'Kolom NIK wajib diisi. Pastikan header kolom Excel adalah "NIK" dan datanya tidak kosong.',
            'nama_kecamatan.required' => 'Kolom Nama Kecamatan wajib diisi.',
            'nama_lengkap.required' => 'Kolom Nama Lengkap wajib diisi.',
            'tipe_rumah_ibadah.in' => 'Tipe Rumah Ibadah harus Masjid atau Mushalla.',
        ];
    }
}
