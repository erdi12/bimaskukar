<?php

namespace App\Imports;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\SktMasjid;
use App\Models\TipologiMasjid;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SktMasjidImport implements ToModel, WithHeadingRow
{
    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip if nama_masjid is empty
        if (empty($row['nama_masjid'])) {
            return null;
        }

        // 1. Lookup Kecamatan
        $kecamatan = Kecamatan::where('kecamatan', 'LIKE', '%'.$row['kecamatan'].'%')->first();
        if (! $kecamatan) {
            // Log warning or skip or set null?
            // Fallback: Try to use 'Tenggarong' or first one? No, validasi required.
            // For now, if invalid, maybe skip or use null (but dB column is required).
            // Let's assume user provides valid data or we simply don't insert if missing required foreign keys.
            Log::warning('Kecamatan not found for: '.$row['kecamatan']);

            return null;
        }

        // 2. Lookup Kelurahan
        $kelurahan = Kelurahan::where('nama_kelurahan', 'LIKE', '%'.$row['kelurahandesa'].'%')->first();
        if (! $kelurahan) {
            Log::warning('Kelurahan not found for: '.$row['kelurahandesa']);

            return null;
        }

        // 3. Lookup Tipologi
        $tipologi = TipologiMasjid::where('nama_tipologi', 'LIKE', '%'.$row['tipologi'].'%')->first();
        if (! $tipologi) {
            Log::warning('Tipologi not found for: '.$row['tipologi']);

            // Try default or return null?
            return null;
        }

        // Check if matched by Nomor ID Masjid (Update) or Create New
        if (!empty($row['nomor_id_masjid'])) {
            $skt = SktMasjid::where('nomor_id_masjid', $row['nomor_id_masjid'])->first();
            if ($skt) {
                $skt->update([
                    'nama_masjid' => $row['nama_masjid'],
                    'alamat_masjid' => $row['alamat'],
                    'kecamatan_id' => $kecamatan->id,
                    'kelurahan_id' => $kelurahan->id,
                    'tipologi_masjid_id' => $tipologi->id,
                ]);

                return $skt;
            }
        }

        return new SktMasjid([
            'nama_masjid' => $row['nama_masjid'],
            'nomor_id_masjid' => $row['nomor_id_masjid'],
            'alamat_masjid' => $row['alamat'],
            'kecamatan_id' => $kecamatan->id,
            'kelurahan_id' => $kelurahan->id,
            'tipologi_masjid_id' => $tipologi->id,
        ]);
    }
}
