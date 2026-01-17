<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Terdaftar Masjid</title>
    <style>
        body {
            font-family: "arial", sans-serif;
            font-size: 11pt;
            line-height: 1.2;
        }

        /* --- Wrapper & Watermark --- */
        .kertas {
            position: relative;
            border: 3px solid black;
            padding: 25px;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .kertas::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ $logoBase64 }}");
            background-repeat: no-repeat;
            background-position: center 35%;
            background-size: 65%;
            opacity: 0.07;
            z-index: -1;
            pointer-events: none;
        }

        /* --- Kop Surat dengan Float --- */
        .kop-surat-container {
            margin-bottom: 10px;
        }

        .logo-kemenag {
            float: left;
            width: 80px;
        }

        .logo-kemenag img {
            width: 80px;
        }

        .kop-text {
            margin-left: -25px;
            text-align: center;
        }

        .kop-surat-container::after {
            content: "";
            display: table;
            clear: both;
        }

        .kop-text h3,
        .kop-text h4,
        .kop-text p {
            margin: 0;
            padding: 0;
        }

        .garis-bawah {
            border-bottom: 1px solid black;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .judul-wrapper {
            text-align: center;
            margin-bottom: 25px;
        }

        .judul-surat {
            font-size: 14pt;
            text-decoration: underline;
            margin: 0;
        }

        .nomor-surat {
            font-size: 12pt;
            font-weight: normal;
            margin-top: 5px;
        }

        .isi-surat {
            text-align: justify;
        }

        /* --- Data Surat dengan Float --- */
        .data-row {
            margin: 15pt 0;
            line-height: 1.5;
        }

        .data-row::after {
            content: "";
            display: table;
            clear: both;
        }

        .data-label {
            float: left;
            width: 170px;
            white-space: nowrap;
        }

        .data-colon {
            float: left;
            width: 15px;
            text-align: center;
        }

        .data-isi {
            float: left;
            max-width: calc(100% - 185px);
            word-wrap: break-word;
        }

        .nowrap {
            white-space: nowrap;
        }

        /* --- Signature Section --- */
        .ttd-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .ttd-col-img {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .ttd-col-text {
            width: 50%;
            text-align: left;
            padding-left: 50px;
            /* Adjust padding to look right */
        }
    </style>
</head>

<body>
    <div class="kertas">
        <!-- --- Kop Surat --- -->
        <div class="kop-surat-container">
            <div class="logo-kemenag">
                <img src="{{ $logoBase64 }}" alt="Logo Kemenag">
            </div>
            <div class="kop-text">
                <p>KEMENTERIAN AGAMA REPUBLIK INDONESIA</p>
                <p style="font-size: 12pt;">KANTOR KEMENTERIAN AGAMA KABUPATEN KUTAI KARTANEGARA</p>
                <p style="font-size: 9pt;">
                    Jalan Muso bin Salim No. 28, Kelurahan Melayu, Kecamatan Tenggarong <br>
                    Telp. (0541) 661092, Whatsapp PTSP : 082149614962
                </p>
            </div>
        </div>

        <div class="garis-bawah"></div>

        <div class="judul-wrapper">
            <h4 class="judul-surat">SURAT KETERANGAN TERDAFTAR</h4>
            <p class="nomor-surat" style="text-align:justify; margin-left:165px;">Nomor : ${nomor_naskah}</p>
        </div>

        <br>
        <div class="isi-surat">
            <p>Yang bertanda tangan dibawah ini :</p>

            <div class="data-row">
                <div class="data-label">Nama</div>
                <div class="data-colon">:</div>
                <div class="data-isi"><b>{{ $nama_pengirim }}</b></div>
            </div>

            <div class="data-row">
                <div class="data-label">NIP</div>
                <div class="data-colon">:</div>
                <div class="data-isi">{{ $nip_pengirim }}</div>
            </div>

            <div class="data-row">
                <div class="data-label">Jabatan</div>
                <div class="data-colon">:</div>
                <div class="data-isi">
                    {{ $jabatan_pengirim }}
                    <span class="nowrap">Provinsi Kalimantan Timur</span>
                </div>
            </div>

            <p>Menerangkan bahwa :</p>

            <div class="data-row">
                <div class="data-label">Nama Masjid</div>
                <div class="data-colon">:</div>
                <div class="data-isi"><b>{{ $sktMasjid->nama_masjid }}</b></div>
            </div>

            <div class="data-row">
                <div class="data-label">Alamat</div>
                <div class="data-colon">:</div>
                <div class="data-isi">
                    {{ $sktMasjid->alamat_masjid }},
                    {{ $sktMasjid->kelurahan->jenis_kelurahan == 'Desa' ? 'Desa' : 'Kel.' }}
                    {{ $sktMasjid->kelurahan->nama_kelurahan }}, Kec. {{ ucwords($sktMasjid->kecamatan->kecamatan) }}
                </div>
            </div>

            @if ($sktMasjid->tipologiMasjid)
                <div class="data-row">
                    <div class="data-label">Tipologi</div>
                    <div class="data-colon">:</div>
                    <div class="data-isi">{{ $sktMasjid->tipologiMasjid->nama_tipologi }}</div>
                </div>
            @endif

            <p>
                Adalah benar telah terdaftar pada Kantor Kementerian Agama Kabupaten Kutai Kartanegara,
                dengan <b>Nomor Identitas Masjid : <span
                        style="font-size: 16pt;">{{ $sktMasjid->nomor_id_masjid }}</span></b>
            </p>

            <p>
                Surat Keterangan Terdaftar ini <b>berlaku untuk 5 ( lima ) tahun</b> terhitung di
                keluarkannya Surat Keterangan Terdaftar ini.
            </p>

            <p>Demikian Surat Keterangan ini dibuat untuk dapat digunakan sebagaimana mestinya.</p>
        </div>

        <table class="ttd-table">
            <tr>
                <td class="ttd-col-img">
                    <!-- Image aligned with TTD -->
                    @if (isset($additionalImageBase64))
                        <img src="{{ $additionalImageBase64 }}" style="width: 120px; height: auto;"
                            alt="Additional Image">
                    @endif
                </td>
                <td class="ttd-col-text">
                    <p>
                        Tenggarong, ${tanggal_naskah}<br>
                        Kepala, <br><br><br><br>
                        <span style="font-weight: bold; text-decoration: underline;">${ttd_pengirim}</span>
                        <br><br><br><br>
                        ${nama_pengirim}
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
