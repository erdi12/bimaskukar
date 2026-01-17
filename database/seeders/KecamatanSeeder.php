<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            'anggana',
            'kembang janggut',
            'kenohan',
            'kota bangun',
            'kota bangun darat',
            'loa janan',
            'loa kulu',
            'marangkayu',
            'muara badak',
            'muara jawa',
            'muara kaman',
            'muara muntai',
            'muara wis',
            'samboja',
            'sangasanga',
            'sebulu',
            'samboja barat',
            'tabang',
            'tenggarong',
            'tenggarong seberang',
        ];

        foreach ($kecamatans as $kecamatan) {
            Kecamatan::create([
                'kecamatan' => $kecamatan,
            ]);
        }
    }
}
