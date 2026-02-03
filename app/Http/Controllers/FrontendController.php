<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        // Di Home, kita tampilkan semua section
        $kepala = Pegawai::where('is_kepala', true)->first();
        // Ambil staff saja (yang BUKAN kepala)
        $staffs = Pegawai::where('is_kepala', false)->orderBy('urutan')->get();

        // Ambil Layanan
        $layanans = \App\Models\Layanan::all();

        return view('frontend.home', compact('kepala', 'staffs', 'layanans'));
    }

    public function profil()
    {
        // Di Halaman Profil sama
        $kepala = Pegawai::where('is_kepala', true)->first();
        $staffs = Pegawai::where('is_kepala', false)->orderBy('urutan')->get();

        return view('frontend.profil', compact('kepala', 'staffs'));
    }

    public function detailLayanan($slug)
    {
        $layanan = \App\Models\Layanan::where('slug', $slug)->firstOrFail();

        return view('frontend.layanan.show', compact('layanan'));
    }

    public function kontak()
    {
        return view('frontend.kontak');
    }

    public function dataKeagamaan(Request $request)
    {
        $tab = $request->query('tab', 'majelis');
        $search = $request->query('search');

        $data = null;

        if ($tab == 'majelis') {
            $query = \App\Models\Sktpiagammt::with(['kecamatan', 'kelurahan']);

            if ($search) {
                $query->where('nama_majelis', 'like', "%{$search}%");
            }
            $data = $query->paginate(9); // 9 items for grid 3x3
        } elseif ($tab == 'masjid') {
            $query = \App\Models\SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid']);
            if ($search) {
                $query->where('nama_masjid', 'like', "%{$search}%");
            }
            $data = $query->paginate(9);
        } elseif ($tab == 'mushalla') {
            $query = \App\Models\SktMushalla::with(['kecamatan', 'kelurahan', 'tipologiMushalla']);
            if ($search) {
                $query->where('nama_mushalla', 'like', "%{$search}%");
            }
            $data = $query->paginate(9);
        }

        // Append query params to pagination links
        if ($data) {
            $data->appends(['tab' => $tab, 'search' => $search]);
        }

        // Get Totals for Dashboard
        $totalMajelis = \App\Models\Sktpiagammt::count();
        $totalMasjid = \App\Models\SktMasjid::count();
        $totalMushalla = \App\Models\SktMushalla::count();

        // Get Summary per Kecamatan
        $kecamatanSummary = \App\Models\Kecamatan::withCount([
            'sktpiagammts as majelis_count',
            'masjids as masjid_count',
            'mushallas as mushalla_count'
        ])->orderBy('kecamatan')->get();

        return view('frontend.data_keagamaan', compact('data', 'tab', 'search', 'totalMajelis', 'totalMasjid', 'totalMushalla', 'kecamatanSummary'));
    }
}
