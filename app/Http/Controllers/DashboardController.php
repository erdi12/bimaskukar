<?php

namespace App\Http\Controllers;

use App\Models\Sktpiagammt;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data Majelis Ta'lim dengan relasi
        $sktpiagammts = Sktpiagammt::with(['kecamatan', 'kelurahan'])->get();
        
        // Hitung total berdasarkan status
        $totalAktif = $sktpiagammts->where('status', 'aktif')->count();
        $totalNonaktif = $sktpiagammts->where('status', 'nonaktif')->count();
        
        // Hitung data per kecamatan
        $kecamatanData = [];
        $kecamatans = Kecamatan::all();
        
        foreach ($kecamatans as $kecamatan) {
            $mtInKecamatan = $sktpiagammts->where('kecamatan_id', $kecamatan->id);
            $kecamatanData[] = [
                'nama' => $kecamatan->kecamatan,
                'total' => $mtInKecamatan->count(),
                'aktif' => $mtInKecamatan->where('status', 'aktif')->count(),
                'nonaktif' => $mtInKecamatan->where('status', 'nonaktif')->count(),
            ];
        }

        // Data untuk pie chart aktif
        $chartDataAktif = collect($kecamatanData)->map(function ($item) {
            return [
                'label' => $item['nama'],
                'value' => $item['aktif']
            ];
        });

        // Data untuk pie chart non-aktif
        $chartDataNonaktif = collect($kecamatanData)->map(function ($item) {
            return [
                'label' => $item['nama'],
                'value' => $item['nonaktif']
            ];
        });

        // Data untuk bar chart
        $chartDataBar = [
            'labels' => collect($kecamatanData)->pluck('nama'),
            'aktif' => collect($kecamatanData)->pluck('aktif'),
            'nonaktif' => collect($kecamatanData)->pluck('nonaktif'),
        ];

        return view('backend.dashboard', compact(
            'sktpiagammts',
            'totalAktif',
            'totalNonaktif',
            'kecamatanData',
            'chartDataAktif',
            'chartDataNonaktif',
            'chartDataBar'
        ));
    }
}