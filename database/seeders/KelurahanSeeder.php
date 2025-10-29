<?php

namespace Database\Seeders;

use App\Models\Kelurahan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelurahans = [
            1 => [ // Anggana
                'Anggana', 'Handil Terusan', 'Kutai Lama', 'Muara Pantuan', 
                'Sepatin', 'Sidomulyo', 'Sungai Mariam', 'Tani Baru'
            ],

            2 => [ // Kembang Janggut
                'Bukit Layang', 'Genting Tanah', 'Hambau', 'Kelekat',
                'Kembang Janggut', 'Loa Sakoh', 'Long Beleh Haloq',
                'Long Beleh Modang', 'Muai', 'Perdana', 'Pulau Pinang'
            ],

            3 => [ // Kenohan
                'Kahala', 'Kahala Ilir', 'Lamin Pulut', 'Lamin Telihan',
                'Semayang', 'Teluk Bingkai', 'Teluk Muda', 'Tuana Tuha', 'Tubuhan'
            ],

            4 => [ // Kota Bangun
                'Kedang Murung', 'Kota Bangun Seberang', 'Kota Bangun Ilir', 'Kota Bangun Ulu',
                'Liang', 'Liang Ulu', 'Loleng', 'Pela', 'Muhuran', 'Sangkuliman', 'Sebelimbingan'
            ],

            5 => [ // Kota Bangun Darat
                'Benua Baru', 'Kedang Ipil', 'Kota Bangun I', 'Kota Bangun II', 'Kota Bangun III',
                'Sari Nadi', 'Sedulang', 'Sukabumi', 'Sumber Sari'
            ],

            6 => [ // Loa Janan
                'Bakungan', 'Batuah', 'Loa Duri Ilir', 'Loa Duri Ulu', 'Loa Janan Ulu',
                'Purwajaya', 'Tani Bhakti', 'Tani Harapan'
            ],

            7 => [ // Loa Kulu
                'Jonggon Desa', 'Sungai Payang', 'Jembayan', 'Loa Kulu Kota', 'Loh Sumber',
                'Ponorogan', 'Rempanga', 'Margahayu', 'Jonggon Jaya', 'Lung Anai', 'Jembayan Tengah',
                'Jembayan Dalam', 'Sepakat', 'Sumber Sari', 'Jongkang'
            ],

            8 => [ // Marang Kayu
                'Bunga Putih', 'Kersik', 'Makarti', 'Perangat Baru', 'Perangat Selatan',
                'Sambera Baru', 'Santan Ilir', 'Santan Tengah', 'Santan Ulu', 'Sebuntal', 'Semangko'
            ],

            9 => [ // Muara Badak
                'Saliki', 'Salo Palai', 'Muara Badak Ulu', 'Muara Badak Ilir',
                'Gas Alam Badak 1', 'Tanjung Limau', 'Badak Baru', 'Batu Batu',
                'Salo Cella', 'Tanah Datar', 'Sungai Bawang', 'Badak Mekar',
                'Persiapan Badak Makmur'
            ],

            10 => [ // Muara Jawa
                'Muara Jawa Ulu', 'Muara Jawa Pesisir', 'Muara Jawa Tengah', 'Muara Jawa Ilir',
                'Dondang', 'Tamapole', 'Muara Kembang', 'Teluk Dalam'
            ],

            11 => [ // Muara Kaman
                'Benua Puhun', 'Bukit Jering', 'Bunga Jadi', 'Kupang Baru', 'Lebaho Ulaq', 'Liang Buaya', 'Menamang Kanan', 'Menamang Kiri', 'Muara Kaman Ilir', 'Muara Kaman Ulu', 'Muara Siran', 'Panca Jaya', 'Puan Cepak', 'Rantau Hempang', 'Sabintulung', 'Sedulang', 'Sidomukti', 'Teratak', 'Tunjungan', 'Cipari Makmur'
            ],

            12 => [ // Muara Muntai
                'Batuq', 'Jantur', 'Jantur Baru', 'Jantur Selatan', 'Kayu Batu', 'Muara Aloh', 'Muara Leka', 'Muara Muntai Ilir', 'Muara Muntai Ulu', 'Perian', 'Pulau Harapan', 'Rebaq Rinding', 'Tanjung Batuq Harapan'
            ],

            13 => [ // Muara Wis
                'Enggelam', 'Lebak Cilong', 'Lebak Mantan', 'Melintang', 'Muara Enggelam', 'Muara Wis', 'Sebemban'
            ],

            14 => [ // Samboja
                'Wonotirto', 'Tanjung Harapan', 'Samboja Kuala', 'Sanipah', 'Handil Baru', 'Muara Sembilang', 'Teluk Pamedas', 'Kampung Lama', 'Handil Baru Darat', 'Sungai Seluang', 'Karya Jaya', 'Bukit Raya', 'Beringin Agung'
            ],

            15 => [ // Sanga Sanga
                'Jawa', 'Sangasanga Dalam', 'Pendingin', 'Sarijaya', 'Sangasanga Muara'
            ],

            16 => [ // Sebulu
                'Selerong', 'Tanjung Harapan', 'Beloro', 'Sebulu Ulu', 'Sebulu Ilir', 'Segihan', 'Sumber Sari', 'Manunggal Daya', 'Giri Agung', 'Senoni', 'Sebulu Modern', 'Sanggulan', 'Lekaq Kidau', 'Mekar Jaya'
            ],

            17 => [ // Samboja Barat
                'Tani Bhakti', 'Amborawang Darat', 'Amborawang Laut', 'Argosari', 'Bukit Merdeka', 'Karya Merdeka', 'Margomulyo', 'Salok Api Darat', 'Salok Api Laut', 'Sungai Merdeka'
            ],

            18 => [ // Tabang
                'Bila Talang', 'Buluk Sen', 'Gunung Sari', 'Kampung Baru', 'Long Lalang', 'Muara Belinau', 'Muara Kebaq', 'Muara Pedohon', 'Muara Ritan', 'Muara Salung', 'Muara Tiq', 'Muara Tuboq', 'Ritan Baru', 'Tukung Ritan', 'Sidomulyo', 'Tabang Lama', 'Umaq Bekuai', 'Umaq Dian', 'Umaq Tukung'
            ],

            19 => [ // Tenggarong
                'Baru', 'Bukit Biru', 'Jahab', 'Loa Ipuh', 'Loa Ipuh Darat', 'Loa Tebu', 'Maluhu', 'Mangkurawang', 'Melayu', 'Panji', 'Sukarame', 'Timbau', 'Bendang Raya', 'Rapak Lambur'
            ],

            20 => [ // Tenggarong Seberang
                'Bangun Rejo', 'Bhuana Jaya', 'Bukit Pariaman', 'Bukit Raya', 'Embalut', 'Karang Tunggal', 'Kerta Buana', 'Loa Lepu', 'Loa Pari', 'Loa Raya', 'Loa Ulung', 'Manunggal Jaya', 'Mulawarman', 'Perjiwa', 'Separi', 'Sukamaju', 'Tanjung Batu', 'Teluk Dalam'
            ]

        ];

        foreach ($kelurahans as $kecamatanId => $daftarKelurahan) {
            foreach ($daftarKelurahan as $nama) {
                Kelurahan::create([
                    'nama_kelurahan' => $nama,
                    'kecamatan_id'   => $kecamatanId,
                ]);
            }
        }
    }
}
