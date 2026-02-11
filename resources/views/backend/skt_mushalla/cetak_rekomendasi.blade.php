<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Surat Rekomendasi Mushalla</title>
    <style>
        body {
            font-family: "arial", sans-serif;
            font-size: 11pt;
            line-height: 1.2;
        }

        @page {
            size: A4;
            margin-top: 4.0cm;
            margin-left: 2.54cm;
            margin-right: 2.54cm;
            margin-bottom: 2.54cm;
        }

        header {
            position: fixed;
            top: -3.5cm;
            left: 0;
            right: 0;
            height: 3.5cm;
        }

        /* --- Wrapper & Watermark --- */
        /* .kertas {
            position: relative;
            border: 3px solid black;
            padding: 25px;
            min-height: 100vh;
            box-sizing: border-box;
        } */

        /* .kertas::before {
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
        } */

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
            line-height: 1;
        }

        .data-row::after {
            content: "";
            display: table;
            clear: both;
        }

        .data-label {
            float: left;
            text-indent: 35px;
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
        <header>
            <div class="kop-surat-container">
                <div class="logo-kemenag">
                    <img src="{{ $logoBase64 }}" alt="Logo Kemenag">
                </div>
                <div class="kop-text">
                    <p>KEMENTERIAN AGAMA REPUBLIK INDONESIA</p>
                    <p style="font-size: 11pt;">KANTOR KEMENTERIAN AGAMA KABUPATEN KUTAI KARTANEGARA</p>
                    <p style="font-size: 9pt;">
                        Jalan Muso bin Salim No. 28, Kelurahan Melayu, Kecamatan Tenggarong <br>
                        Telp. (0541) 661092, Whatsapp PTSP : 082149614962 <br>
                        Website :
                        <a href="https://kemenagkukar.id">https://kemenagkukar.id</a>
                    </p>
                </div>
            </div>

            <div class="garis-bawah"></div>
        </header>

        <div class="judul-wrapper">
            <h4 class="judul-surat">REKOMENDASI</h4>
            <p class="nomor-surat" style="text-align:justify; margin-left:165px;"><b>Nomor :</b> {{ $nomor_naskah }}</p>
        </div>


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
                    <span class="wrap">{{ $jabatan_pengirim }}
                        Provinsi Kalimantan Timur</span>
                </div>
            </div>

            <p>Dengan ini memberikan rekomendasi kepada :</p>

            <div class="data-row">
                <div class="data-label">Nama Mushalla</div>
                <div class="data-colon">:</div>
                <div class="data-isi"><b>{{ $sktMushalla->nama_mushalla }}</b></div>
            </div>

            <div class="data-row">
                <div class="data-label">Nomor ID Mushalla</div>
                <div class="data-colon">:</div>
                <div class="data-isi">{{ $sktMushalla->nomor_id_mushalla }}</div>
            </div>

            @if ($sktMushalla->tipologiMushalla)
                <div class="data-row">
                    <div class="data-label">Tipologi</div>
                    <div class="data-colon">:</div>
                    <div class="data-isi">{{ $sktMushalla->tipologiMushalla->nama_tipologi }}</div>
                </div>
            @endif

            <div class="data-row">
                <div class="data-label">Alamat</div>
                <div class="data-colon">:</div>
                <div class="data-isi">
                    {{ $sktMushalla->alamat_mushalla }},
                    {{ $sktMushalla->kelurahan->jenis_kelurahan == 'Desa' ? 'Desa' : 'Kel.' }}
                    {{ $sktMushalla->kelurahan->nama_kelurahan }}, Kec.
                    {{ ucwords($sktMushalla->kecamatan->kecamatan) }}
                </div>
            </div>


            <p>
                Untuk dapat mengajukan permohonan dan menerima bantuan dana dari Gubernur Provinsi Kalimantan Timur,
                dalam rangka meningkatkan kualitas sarana dan prasarana rumah ibadah bagi masyarakat muslim di
                sekitarnya
            </p>

            <p>
                Demikian rekomendasi ini dibuat untuk dapat digunakan sebagaimana mestinya.
            </p>
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
                        Tenggarong, {{ $tanggal_naskah }}<br>
                        Kepala, <br><br><br><br>
                        <span style="font-weight: bold; text-decoration: underline;">{{ $ttd_pengirim }}</span>
                        <br><br><br><br>
                        {{ $nama_pengirim }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
