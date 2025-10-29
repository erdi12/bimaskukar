<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Surat Keterangan Terdaftar</title>
<style>
    /* Atur margin kertas */
    /* @page {
        margin-top: 1.7cm;
        margin-bottom: 0.9cm;
        margin-left: 1.94cm;
        margin-right: 1.94cm;
    } */

    body {
        font-family: "arial", sans-serif;
        font-size: 11pt;
        line-height: 1.2;
        position: relative;
        /* Tambahkan padding untuk menjauhkan konten dari bingkai */
        padding: 25px;
        box-sizing: border-box;
    }

    .bg-kertas {
        position: absolute;
        margin-top: 1.3em; 
        left: 0;
        width: 100%; 
        height: 100%;
        background-image: url("{{ $logoBase64 }}");
        background-repeat: no-repeat;
        background-position: center 35%;
        background-size: 65%;
        opacity: 0.07;
        z-index: -1;
    }

    /* .bingkai {
        position: absolute;
        background-image: url("bingkai.svg");
        left: 0;
        top: 0;
    } */

    .kop-surat {
        position: relative;
        text-align: center;
        padding-left: 70px; /* kasih ruang kiri sebesar logo */
        margin-bottom: 10px; /* Tambahkan margin bawah */
    }
    .logo-kemenag {
        position: absolute;
        left: 0;
        top: 0;
        width: 80px;
    }

    .kop-text {
        text-align: center;
        flex: 1;
    }


    .kop-surat h3, 
    .kop-surat h4, 
    .kop-surat p {
        margin: 0;
        padding: 0;
    }

    /* Garis bawah ganda */
    .garis-bawah {
        border-bottom: 1px solid black; /* Garis bawah pertama */
        margin-top: 5px;
        margin-bottom: 15px;
    }

.judul-wrapper {
    text-align: center;
    margin-bottom: 25px;
    position: relative;
}

.bingkai {
      position: fixed;
      top: 20px;  /* Ubah dari 10px ke 20px */
      left: 20px;  /* Ubah dari 10px ke 20px */
      right: 20px;  /* Ubah dari 10px ke 20px */
      bottom: 20px;  /* Ubah dari 10px ke 20px */
      border: 3px solid black;
      z-index: 999;
      pointer-events: none;
  }


.judul-surat {
    font-size: 14pt;
    text-decoration: underline;
    margin: 0;
    display: inline-block;   /* biar ukurannya sesuai teks */
    position: relative;
}

.kertas {
    border: 2px solid black; /* tebal garis */
    padding: 20px;           /* jarak isi dengan garis */
    min-height: 100%;        /* biar nutup full halaman */
    box-sizing: border-box;  /* padding dihitung dalam lebar */
}



.nomor-surat {
    position: absolute;
    left: 0;                /* mulai dari sisi kiri kata SURAT */
    bottom: -20px;          /* atur jarak vertikal ke bawah judul */
    font-size: 12pt;
    font-weight: normal;
}

    .nomor-surat .label { white-space: nowrap; }

    /* Isi surat */
    .isi-surat {
        text-align: justify;
        /* Tambahkan margin untuk menjauhkan dari bingkai */
        margin: 0 10px;
    }

    .data-surat {
        display: grid;
        grid-template-columns: 170px 15px auto; /* label | : | isi */
        margin: 15pt 0;
    }
    .data-surat .label { white-space: nowrap; }
    .nowrap { white-space: nowrap; }

    /* Tanda tangan */
    .ttd {
        margin-top: 40px;
        text-align: left;
        margin-left: 350px;
        /* Pastikan tanda tangan tidak terlalu ke bawah */
        margin-bottom: 30px;
    }
  </style>
</head>
<body>
    <div class="bingkai"></div>
    <div class="bg-kertas"></div>

    <div class="kop-surat">
        <img src="{{ $logoBase64 }}" class="logo-kemenag" alt="Logo Kemenag">
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
        <h4 class="judul-surat">
            SURAT KETERANGAN TERDAFTAR
            <span class="nomor-surat">Nomor : ${nomor_naskah}</span>
        </h4>
    </div>

    <br>
    <div class="isi-surat">
        <p>Yang bertanda tangan dibawah ini :</p>

        <p class="data-surat">
            <span class="label">Nama</span>
            <span>:</span>
            <span><b>Ariyadi F, S.Ag.</b></span>
        </p>

        <p class="data-surat">
            <span class="label">NIP</span>
            <span>:</span>
            <span>19770805 199803 1 003</span>
        </p>

        <p class="data-surat">
            <span class="label">Jabatan</span>
            <span>:</span>
            <span>
              Kepala Kantor Kementerian Agama Kabupaten Kutai Kartanegara
              <span class="nowrap">Provinsi Kalimantan Timur</span>
            </span>
        </p>

        <p>Menerangkan bahwa :</p>

        <p class="data-surat">
            <span class="label">Majelis Ta'lim</span>
            <span>:</span>
            <span><b>{{ $sktpiagammt->nama_majelis }}</b></span>
        </p>

        <p class="data-surat">
            <span class="label">Alamat</span>
            <span>:</span>
            <span>
                {{ $sktpiagammt->alamat }}, {{ $tipeWilayah == 'desa' ? 'Desa' : 'Kel.' }} {{ $sktpiagammt->kelurahan->nama_kelurahan }}, Kec. {{ ucwords($sktpiagammt->kecamatan->kecamatan) }}
                {{-- <span class="nowrap">{{ $tipeWilayah == 'desa' ? 'Desa' : 'Kel.' }} {{ $sktpiagammt->kelurahan->nama_kelurahan }}, Kec. {{ ucwords($sktpiagammt->kecamatan->kecamatan) }}</span> --}}
            </span>
        </p>

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
</body>
</html>