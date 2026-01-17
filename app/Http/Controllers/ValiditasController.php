<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValiditasController extends Controller
{
    public function index(Request $request)
    {
        $result = null;
        $type = null;
        $keyword = null;

        if ($request->has('type') && $request->has('keyword')) {
            $type = $request->type;
            $keyword = trim($request->keyword);

            if ($keyword) {
                if ($type == 'majelis_taklim') {
                    $result = \App\Models\Sktpiagammt::with(['kecamatan', 'kelurahan'])
                        ->where('nomor_statistik', 'like', "%$keyword%")
                        ->orWhere('nama_majelis', 'like', "%$keyword%") // Optional: search by name too?
                        ->first();
                } elseif ($type == 'masjid') {
                    $result = \App\Models\SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid'])
                        ->where('nomor_id_masjid', 'like', "%$keyword%")
                        // ->orWhere('nama_masjid', 'like', "%$keyword%")
                        ->first();
                } elseif ($type == 'mushalla') {
                    $result = \App\Models\SktMushalla::with(['kecamatan', 'kelurahan', 'tipologiMushalla'])
                        ->where('nomor_id_mushalla', 'like', "%$keyword%")
                        // ->orWhere('nama_mushalla', 'like', "%$keyword%")
                        ->first();
                } elseif ($type == 'marbot') {
                    $result = \App\Models\Marbot::with(['kecamatan', 'kelurahan', 'masjid', 'mushalla'])
                        ->where('nik', 'like', "%$keyword%")
                        ->first();
                }
            }
        }

        return view('frontend.cek_validitas', compact('result', 'type', 'keyword'));
    }
}
