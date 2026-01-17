@extends('layout.appv2')
@section('title', 'Dashboard')

@section('content')
    @include('layout.dashboard_admin', [
        'sktpiagammts' => $sktpiagammts ?? [],
        'totalAktif' => $totalAktif ?? 0,
        'totalNonaktif' => $totalNonaktif ?? 0,
        'totalBelumUpdate' => $totalBelumUpdate ?? 0,
        'kecamatanData' => $kecamatanData ?? [],
        'totalMasjid' => $totalMasjid ?? 0,
        'totalMushalla' => $totalMushalla ?? 0,
        'masjidByTipologi' => $masjidByTipologi ?? [],
        'mushallaByTipologi' => $mushallaByTipologi ?? [],
        'rumahIbadahPerKecamatan' => $rumahIbadahPerKecamatan ?? [],
        'earlyWarnings' => $earlyWarnings ?? [],
    ])
@endsection
