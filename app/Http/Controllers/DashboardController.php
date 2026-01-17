<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sktpiagammt;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. Statistik Kartu Atas ---
        // Early Warning System: Majelis Taklim upcoming expiration (30 days)
        $earlyWarnings = Sktpiagammt::with(['kecamatan', 'kelurahan'])
            ->whereBetween('mendaftar_ulang', [now(), now()->addDays(30)])
            ->where('status', '!=', 'nonaktif') // Only show if not already nonaktif
            ->orderBy('mendaftar_ulang', 'asc')
            ->get();

        $totalMT = Sktpiagammt::count();
        $totalAktif = Sktpiagammt::where('status', 'aktif')->count();
        $totalNonaktif = Sktpiagammt::where('status', 'nonaktif')->count();
        $totalBelumUpdate = Sktpiagammt::where('status', 'belum_update')->count();

        $totalKecamatan = Kecamatan::count();
        $totalKelurahan = Kelurahan::count();
        $totalUser = User::count();

        // --- 2. Data Per Kecamatan (Optimized) ---
        // Menggunakan withCount untuk menghindari N+1 Query
        $kecamatans = Kecamatan::withCount([
            'sktpiagammts as total',
            'sktpiagammts as aktif' => function ($q) {
                $q->where('status', 'aktif');
            },
            'sktpiagammts as nonaktif' => function ($q) {
                $q->where('status', 'nonaktif');
            },
            'sktpiagammts as belum_update' => function ($q) {
                $q->where('status', 'belum_update');
            },
        ])->orderBy('total', 'desc')->get();

        // Map ke format array sederhana
        $kecamatanData = $kecamatans->map(function ($kec) {
            return [
                'nama' => $kec->kecamatan,
                'total' => $kec->total,
                'aktif' => $kec->aktif,
                'nonaktif' => $kec->nonaktif,
                'belum_update' => $kec->belum_update,
            ];
        })->values();

        // --- 3. Data Rumah Ibadah ---
        $totalMasjid = \App\Models\SktMasjid::count();
        $totalMushalla = \App\Models\SktMushalla::count();

        // Tipologi Masjid
        $masjidByTipologi = \App\Models\SktMasjid::join('tipologi_masjids', 'skt_masjids.tipologi_masjid_id', '=', 'tipologi_masjids.id')
            ->selectRaw('tipologi_masjids.nama_tipologi as nama, count(*) as total')
            ->groupBy('tipologi_masjids.nama_tipologi')
            ->get();

        // Tipologi Mushalla
        $mushallaByTipologi = \App\Models\SktMushalla::join('tipologi_mushallas', 'skt_mushallas.tipologi_mushalla_id', '=', 'tipologi_mushallas.id')
            ->selectRaw('tipologi_mushallas.nama_tipologi as nama, count(*) as total')
            ->groupBy('tipologi_mushallas.nama_tipologi')
            ->get();

        // --- 4. Data Detail Rumah Ibadah per Kecamatan (with Tipologi) ---
        $listTipologiMasjid = \App\Models\TipologiMasjid::all();
        $listTipologiMushalla = \App\Models\TipologiMushalla::all();

        // Build dynamic withCount array
        $riCounts = [
            'masjids as total_masjid',
            'mushallas as total_mushalla',
        ];

        // Add counts for each Masjid Tipologi
        foreach ($listTipologiMasjid as $tm) {
            $riCounts['masjids as count_masjid_' . $tm->id] = function ($q) use ($tm) {
                $q->where('tipologi_masjid_id', $tm->id);
            };
        }

        // Add counts for each Mushalla Tipologi
        foreach ($listTipologiMushalla as $tmu) {
            $riCounts['mushallas as count_mushalla_' . $tmu->id] = function ($q) use ($tmu) {
                $q->where('tipologi_mushalla_id', $tmu->id);
            };
        }

        // Fetch Data
        $rumahIbadahPerKecamatan = Kecamatan::withCount($riCounts)->orderBy('total_masjid', 'desc')->get();

        // Transform for Chart (keep simple key structure for JS compatibility if needed, OR just pass full object)
        // Since we need full object for Table, and Chart uses a map...
        // Let's create a specific chart data set if strict separation is needed, 
        // BUT actually the existing JS uses 'nama', 'total_masjid', 'total_mushalla'.
        // Eloquent serialization provides these fields automatically.
        // So we can pass the collection directly.
        // However, existing JS expects 'nama', NOT 'kecamatan' (model attribute is 'kecamatan').
        // So we must append 'nama' attribute or map it.
        $rumahIbadahPerKecamatan->transform(function($k) {
            $k->nama = $k->kecamatan; // JS compatibility
            return $k;
        });

        return view('backend.dashboard_v2', compact(
            'totalMT',
            'totalAktif',
            'totalNonaktif',
            'totalBelumUpdate',
            'totalKecamatan',
            'totalKelurahan',
            'totalUser',
            'kecamatanData',
            'totalMasjid',
            'totalMushalla',
            'masjidByTipologi',
            'mushallaByTipologi',
            'rumahIbadahPerKecamatan',
            'listTipologiMasjid',
            'listTipologiMushalla',
            'earlyWarnings'
        ))->with('sktpiagammts', $totalMT);
    }
}
