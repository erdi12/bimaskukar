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
            src: url('data:font/ttf;base64,{{ $fontData }}') format('truetype');
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
            margin-top: 0.7cm;
            margin-bottom: 0.2cm;
            margin-left: 0.24cm;
            margin-right: 0.24cm;
        }
        
        .bg-kertas {
            position: fixed;
            /* top: 0; left: 0; */
            margin-top: 70px;
            width: 100%; height: 100%;
            background-image: url("{{ $logoBase64 }}");
            background-repeat: no-repeat;
            background-position: center 35%;
            background-size: 60%;
            opacity: 0.07;
            z-index: -1;
        }
        .kertas {
            border: 2px solid black; /* tebal garis */
            padding: 20px;           /* jarak isi dengan garis */
            min-height: 100%;        /* biar nutup full halaman */
            box-sizing: border-box;  /* padding dihitung dalam lebar */
        }
        .indented-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            text-indent: 40px;       /* indentasi paragraf */
            text-align: justify;     /* rata kanan kiri */
            line-height: 1.5;        /* jarak antar baris */
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
.row-alamat {
    display: flex;
    line-height: 1.5;
    font-size: 12pt;
    margin-bottom: 10px;
    align-items: flex-start; /* biar semua rata atas */
}

.label {
    min-width: 160px;   /* panjang area label */
    white-space: nowrap;
}

.colon {
    width: 70px;        /* kolom khusus untuk ":" */
    text-align: center;
}

.isi {
    flex: 1;            /* biar sisa ruang dipakai isi */
    word-wrap: break-word;
    white-space: normal;
}



    </style>
</head>
<body>
    <div class="bg-kertas"></div>
    <div class="kertas">
        <div class="isi-kertas">
            <p >Nomor : ${nomor_naskah}</p>
            <div class="header">
                <p>KEMENTERIAN AGAMA REPUBLIK INDONESIA</p>
                <P>KANTOR KEMENTERIAN KABUPATEN KUTAI KARTANEGARA</P>
                <img src="{{ $logoBase64 }}" alt="" width="100">
                <p style="font-family: Arial, Helvetica, sans-serif;" class="mt-3">PIAGAM PENYELENGGARAAN</p>
                <p style="font-family: Arial, Helvetica, sans-serif;">MAJELIS TA'LIM</p>
            </div>
            <p class="indented-text">Kepala Kantor Kementerian Agama Kabupaten Kutai Kartanegara dengan merujuk kepada peraturan Menteri Agama Nomor 29 Tahun 2019, dengan ini memberikan Piagam Penyelenggaraan Majelis Ta'lim kepada :</p>
            <div class="bio">
                <p>1.<span style="margin-left: 5px; margin-right: 64px;">Nama Majelis Ta'lim</span> : <span  style="margin-right: 28px;"></span><strong>{{ $sktpiagammt->nama_majelis }}</strong></p>
                <p>2.<span style="margin-left: 5px; margin-right: 97px;">Nomor Statistik</span> : <span style="margin-right: 28px;"></span><strong>{{ $sktpiagammt->nomor_statistik }}</strong></p>
                <p>3.<span style="margin-left: 5px;">Alamat</span></p>
                <!-- Di bagian tabel -->
                {{-- <p>a. Jalan <span style="margin-left: 143px;"> : <span class="text-wrap">{{ $sktpiagammt->alamat }}</span></p> --}}
                <div class="bio-indent">
                    <div class="row-alamat">
                        <div class="label">a. Jalan</div>
                        <div class="colon">:</div>
                        <div class="isi">{{ $sktpiagammt->alamat }}</div>
                    </div>
                    <div class="row-alamat">
                        <div class="label">b. Kelurahan/Desa</div>
                        <div class="colon">:</div>
                        <div class="isi">{{ $sktpiagammt->kelurahan->nama_kelurahan }}</div>
                    </div>
                    <div class="row-alamat">
                        <div class="label">c. Kecamatan</div>
                        <div class="colon">:</div>
                        <div class="isi">{{ ucwords($sktpiagammt->kecamatan->kecamatan) }}</div>
                    </div>
                    <div class="row-alamat">
                        <div class="label">d. Kabupaten</div>
                        <div class="colon">:</div>
                        <div class="isi">Kutai Kartanegara</div>
                    </div>
                </div>
                <p>4.<span style="margin-left: 5px; margin-right: 50px;">Tanggal/Tahun Berdiri</span> : <span style="margin-left: 28px;">{{ \Carbon\Carbon::parse($sktpiagammt->tanggal_berdiri)->locale('id')->isoFormat('D MMMM Y') }}</span></p>
            </div>
            <p class="indented-text">Kepada Majelis Ta’lim tersebut diberikan hak untuk menyelenggarakan pendidikan dan pengajaran Agama Islam sesuai dengan aturan yang berlaku selama tidak melanggar ketentuan, Undang-Undang dan melakukan ajaran penyimpangan.</p>
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