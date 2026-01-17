<?php

namespace App\Imports;

use App\Models\SktMushalla;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\TipologiMushalla;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class SktMushallaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip if nama_mushalla is empty
        if (empty($row['nama_mushalla'])) {
            return null;
        }

        // 1. Lookup Kecamatan
        $kecamatan = Kecamatan::where('kecamatan', 'LIKE', '%' . $row['kecamatan'] . '%')->first();
        if (!$kecamatan) {
             Log::warning("Kecamatan not found for: " . $row['kecamatan']);
             return null;
        }

        // 2. Lookup Kelurahan
        $kelurahan = Kelurahan::where('nama_kelurahan', 'LIKE', '%' . $row['kelurahandesa'] . '%')->first();
        if (!$kelurahan) {
             Log::warning("Kelurahan not found for: " . $row['kelurahandesa']);
             return null;
        }

        // 3. Lookup Tipologi
        $tipologi = TipologiMushalla::where('nama_tipologi', 'LIKE', '%' . $row['tipologi'] . '%')->first();
        if (!$tipologi) {
             Log::warning("Tipologi not found for: " . $row['tipologi']);
             return null;
        }
        
        // Check if matched by Nomor ID Mushalla (Update) or Create New
        if (!empty($row['nomor_id_mushalla'])) {
            $skt = SktMushalla::where('nomor_id_mushalla', $row['nomor_id_mushalla'])->first();
            if ($skt) {
                $skt->update([
                    'nama_mushalla' => $row['nama_mushalla'],
                    'alamat_mushalla' => $row['alamat'],
                    'kecamatan_id' => $kecamatan->id,
                    'kelurahan_id' => $kelurahan->id,
                    'tipologi_mushalla_id' => $tipologi->id,
                ]);

                return $skt;
            }
        }

        return new SktMushalla([
            'nama_mushalla'     => $row['nama_mushalla'],
            'nomor_id_mushalla' => $row['nomor_id_mushalla'],
            'alamat_mushalla'   => $row['alamat'],
            'kecamatan_id'    => $kecamatan->id,
            'kelurahan_id'    => $kelurahan->id,
            'tipologi_mushalla_id' => $tipologi->id,
        ]);
    }
}
