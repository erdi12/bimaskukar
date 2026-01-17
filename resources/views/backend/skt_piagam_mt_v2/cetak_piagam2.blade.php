<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    @font-face {
        font-family: 'ImprintMTShadow';
        src: url('{{ asset('fonts/imprint-mt-shadow.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
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
        min-height: 95%;        /* biar nutup full halaman */
        box-sizing: border-box;  /* padding dihitung dalam lebar */
    }
</style>
<body>
    <div class="bg-kertas"></div>
    <div class="kertas">
        
    </div>
    
</body>
</html>