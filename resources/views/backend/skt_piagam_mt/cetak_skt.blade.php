<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Surat Keterangan Terdaftar</title>
<style>
    body {
        font-family: "arial", sans-serif;
        font-size: 11pt;
        line-height: 1.2;
    }

    /* --- Wrapper & Watermark (Tidak Berubah) --- */
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

    /* --- AWAL PERUBAAN: Kop Surat dengan Float --- */
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
        margin-left: -25px; /* Beri jarak dari logo yang di-float */
        text-align: center;
    }
    /* Clearfix untuk memastikan elemen di bawahnya tidak naik */
    .kop-surat-container::after {
        content: "";
        display: table;
        clear: both;
    }
    /* --- AKHIR PERUBAAN: Kop Surat --- */

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

    /* --- AWAL PERUBAAN: Data Surat dengan Float --- */
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
        width: 170px; /* Lebar tetap untuk label */
        white-space: nowrap;
    }
    .data-colon {
        float: left;
        width: 15px; /* Lebar tetap untuk titik dua */
        text-align: center;
    }
    .data-isi {
        float: left;
        max-width: calc(100% - 185px); /* Sisa lebar (100% - 170px - 15px) */
        word-wrap: break-word;
    }
    .nowrap { white-space: nowrap; }
    /* --- AKHIR PERUBAAN: Data Surat --- */

    .ttd {
        margin-top: 40px;
        text-align: left;
        margin-left: 350px;
        margin-bottom: 30px;
    }
  </style>
</head>
<body>
    <div class="kertas">
        <!-- --- AWAL PERUBAAN HTML: Kop Surat --- -->
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
        <!-- --- AKHIR PERUBAAN HTML: Kop Surat --- -->

        <div class="garis-bawah"></div>

        <div class="judul-wrapper">
            <h4 class="judul-surat">SURAT KETERANGAN TERDAFTAR</h4>
            <p class="nomor-surat" style="text-align:justify; margin-left:165px;">Nomor : ${nomor_naskah}</p>
        </div>

        <br>
        <div class="isi-surat">
            <p>Yang bertanda tangan dibawah ini :</p>

            <!-- --- AWAL PERUBAAN HTML: Data Surat --- -->
            <div class="data-row">
                <div class="data-label">Nama</div>
                <div class="data-colon">:</div>
                <div class="data-isi"><b>Ariyadi F, S.Ag.</b></div>
            </div>

            <div class="data-row">
                <div class="data-label">NIP</div>
                <div class="data-colon">:</div>
                <div class="data-isi">19770805 199803 1 003</div>
            </div>

            <div class="data-row">
                <div class="data-label">Jabatan</div>
                <div class="data-colon">:</div>
                <div class="data-isi">
                  Kepala Kantor Kementerian Agama Kabupaten Kutai Kartanegara
                  <span class="nowrap">Provinsi Kalimantan Timur</span>
                </div>
            </div>
            <!-- --- AKHIR PERUBAAN HTML: Data Surat --- -->

            <p>Menerangkan bahwa :</p>

            <!-- --- AWAL PERUBAAN HTML: Data Surat (Lanjutan) --- -->
            <div class="data-row">
                <div class="data-label">Majelis Ta'lim</div>
                <div class="data-colon">:</div>
                <div class="data-isi"><b>{{ $sktpiagammt->nama_majelis }}</b></div>
            </div>

            <div class="data-row">
                <div class="data-label">Alamat</div>
                <div class="data-colon">:</div>
                <div class="data-isi">
                    {{ $sktpiagammt->alamat }}, {{ $sktpiagammt->kelurahan->jenis_kelurahan == 'Desa' ? 'Desa' : 'Kel.' }} {{ $sktpiagammt->kelurahan->nama_kelurahan }}, Kec. {{ ucwords($sktpiagammt->kecamatan->kecamatan) }}
                </div>
            </div>
            <!-- --- AKHIR PERUBAAN HTML: Data Surat (Lanjutan) --- -->

            <p>
              Adalah benar telah terdaftar pada Kantor Kementerian Agama Kabupaten Kutai Kartanegara,
              dengan <b>Nomor Identitas Statistik : <span style="font-size: 16pt;">{{ $sktpiagammt->nomor_statistik }}</span></b>
            </p>

            <p>
              Surat Keterangan Terdaftar ini <b>berlaku untuk 5 ( lima ) tahun</b> terhitung di
              keluarkannya Surat Keterangan Terdaftar ini.
            </p>

            <p>Demikian Surat Keterangan ini dibuat untuk dapat digunakan sebagaimana mestinya.</p>
        </div>

        <div class="ttd">
            <p>
                Tenggarong, ${tanggal_naskah} <br>
                Kepala, <br><br><br><br>
                <span style="margin-left: 20px;">${ttd_pengirim}</span> <br><br><br><br>
                ${nama_pengirim}
            </p>
        </div>
    </div>
</body>
</html>

