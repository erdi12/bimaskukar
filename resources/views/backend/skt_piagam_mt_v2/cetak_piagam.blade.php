<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>

    <!-- Di bagian style -->
    <style>
        @font-face {
            font-family: 'ImprintMTShadow';
            src: url('{{ public_path('fonts/imprint-mt-shadow.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .header {
            text-align: center;
            font-family: 'ImprintMTShadow', serif;
            line-height: 0;
            margin-top: 0pt;
            margin-bottom: 0pt;
            line-height: 1;
        }

        .isi-kertas {
            font-family: Arial, Helvetica, sans-serif;
            margin-top: 0.1cm;
            margin-bottom: 0.2cm;
            margin-left: 0.24cm;
            margin-right: 0.24cm;
        }

        .bg-kertas {
            position: fixed;
            /* top: 0; left: 0; */
            margin-top: 70px;
            width: 100%;
            height: 100%;
            background-image: url("{{ $logoBase64 }}");
            background-repeat: no-repeat;
            background-position: center 35%;
            background-size: 60%;
            opacity: 0.07;
            z-index: -1;
        }

        .kertas {
            border: 2px solid black;
            /* tebal garis */
            padding: 20px;
            /* jarak isi dengan garis */
            min-height: 90%;
            /* biar nutup full halaman */
            box-sizing: border-box;
            /* padding dihitung dalam lebar */
        }

        .indented-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            text-indent: 40px;
            /* indentasi paragraf */
            text-align: justify;
            /* rata kanan kiri */
            line-height: 1.5;
            /* jarak antar baris */
        }

        .bio {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            margin-left: 35px;
            /* text-indent: 45px;    */
            line-height: 1;
        }

        .bio-indent {
            margin-left: 35px;
        }

        .ttd {
            font-family: Arial, Helvetica, sans-serif;
            /* margin-top: 5px; */
            text-align: left;
            margin-left: 350px;
        }

        /* --- AWAL PERUBAAN --- */
        /* CSS Flexbox lama dihapus dan diganti dengan CSS untuk tabel */

        /* Hapus CSS ini:
        .row-alamat { ... }
        .label { ... }
        .colon { ... }
        .isi { ... }
        */

        /* Ganti dengan CSS baru untuk tabel */
        .alamat-table {
            width: 100%;
            border-collapse: collapse;
            /* Menghilangkan jarak antar sel */
            font-size: 12pt;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .alamat-table td {
            padding: 0;
            /* Hapus padding default tabel */
            vertical-align: top;
            /* Sejajarkan teks di atas, mirip 'align-items: flex-start' */
        }

        .alamat-table .label-td {
            width: 160px;
            /* Lebar tetap untuk label */
            white-space: nowrap;
            /* Mencegah teks label pecah baris */
        }

        .alamat-table .colon-td {
            width: 70px;
            /* Lebar tetap untuk titik dua */
            text-align: center;
            /* Ratakan tengah titik dua */
        }

        /* --- AKHIR PERUBAAN --- */
    </style>
</head>

<body>
    <div class="bg-kertas"></div>
    <div class="kertas">
        <div class="isi-kertas">
            <p>Nomor : ${nomor_naskah}</p>
            <div class="header">
                <p>KEMENTERIAN AGAMA REPUBLIK INDONESIA</p>
                <P>KANTOR KEMENTERIAN KABUPATEN KUTAI KARTANEGARA</P>
                <img src="{{ $logoBase64 }}" alt="" width="100">
                <p style="font-family: Arial, Helvetica, sans-serif;" class="mt-3">PIAGAM PENYELENGGARAAN</p>
                <p style="font-family: Arial, Helvetica, sans-serif;">MAJELIS TA'LIM</p>
            </div>
            <p class="indented-text">Kepala Kantor Kementerian Agama Kabupaten Kutai Kartanegara dengan merujuk kepada
                peraturan Menteri Agama Nomor 29 Tahun 2019, dengan ini memberikan Piagam Penyelenggaraan Majelis Ta'lim
                kepada :</p>
            <div class="bio">
                <table class="alamat-table">
                    <tr>
                        <td style="width: 230px; vertical-align: top;">1. Nama Majelis Ta'lim</td>
                        <td class="colon-td">:</td>
                        <td style="vertical-align: top;"><strong>{{ $sktpiagammt->nama_majelis }}</strong></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">2. Nomor Statistik</td>
                        <td class="colon-td">:</td>
                        <td style="vertical-align: top;">{{ $sktpiagammt->nomor_statistik }}</td>
                    </tr>
                </table>
                <p>3.<span style="margin-left: 5px;">Alamat</span></p>

                <!-- --- AWAL PERUBAAN DI HTML --- -->
                <!-- Struktur div lama diganti dengan tabel -->
                <div class="bio-indent">
                    <table class="alamat-table">
                        <tr>
                            <td class="label-td">a. Jalan</td>
                            <td class="colon-td">:</td>
                            <td>{{ $sktpiagammt->alamat }}</td>
                        </tr>
                        <tr>
                            <td class="label-td">b. Kelurahan/Desa</td>
                            <td class="colon-td">:</td>
                            <td>{{ $sktpiagammt->kelurahan->nama_kelurahan }}</td>
                        </tr>
                        <tr>
                            <td class="label-td">c. Kecamatan</td>
                            <td class="colon-td">:</td>
                            <td>{{ ucwords($sktpiagammt->kecamatan->kecamatan) }}</td>
                        </tr>
                        <tr>
                            <td class="label-td">d. Kabupaten</td>
                            <td class="colon-td">:</td>
                            <td>Kutai Kartanegara</td>
                        </tr>
                    </table>
                </div>
                <!-- --- AKHIR PERUBAAN DI HTML --- -->

                <p>4.<span style="margin-left: 5px; margin-right: 46px;">Tanggal/Tahun Berdiri</span> : <span
                        style="margin-left: 28px;">{{ \Carbon\Carbon::parse($sktpiagammt->tanggal_berdiri)->locale('id')->isoFormat('D MMMM Y') }}</span>
                </p>
            </div>
            <p class="indented-text">Kepada Majelis Ta’lim tersebut diberikan hak untuk menyelenggarakan pendidikan dan
                pengajaran Agama Islam sesuai dengan aturan yang berlaku selama tidak melanggar ketentuan, Undang-Undang
                dan melakukan ajaran penyimpangan.</p>
            <div class="ttd">
                <p>
                    Tenggarong, ${tanggal_naskah} <br>
                    Kepala, <br><br><br><br>
                    <span style="margin-left: 20px;">${ttd_pengirim}</span> <br><br><br><br>
                    ${nama_pengirim}
                </p>
            </div>
        </div>

    </div>
</body>

</html>
