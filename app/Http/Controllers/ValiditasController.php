<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValiditasController extends Controller
{
    /**
     * Tampilkan form pencarian
     */
    public function index()
    {
        return view('frontend.cek_validitas');
    }

    /**
     * Proses pencarian dan redirect ke URL dengan UUID
     */
    public function search(Request $request)
    {
        $request->validate([
            'type' => 'required|in:majelis_taklim,masjid,mushalla,marbot',
            'keyword' => 'required|string|max:255'
        ]);

        $type = $request->type;
        $keyword = trim($request->keyword);

        // Log pencarian untuk monitoring keamanan
        Log::channel('daily')->info('Cek Validitas Search', [
            'ip' => $request->ip(),
            'type' => $type,
            'keyword' => $keyword,
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $result = null;

        // Cari data berdasarkan keyword
        if ($type == 'majelis_taklim') {
            $result = \App\Models\Sktpiagammt::where('nomor_statistik', $keyword)
                ->orWhere('nama_majelis', 'like', "%$keyword%")
                ->first();
        } elseif ($type == 'masjid') {
            $result = \App\Models\SktMasjid::where('nomor_id_masjid', $keyword)->first();
        } elseif ($type == 'mushalla') {
            $result = \App\Models\SktMushalla::where('nomor_id_mushalla', $keyword)->first();
        } elseif ($type == 'marbot') {
            $result = \App\Models\Marbot::where('nik', $keyword)->first();
        }

        // Log hasil pencarian
        Log::channel('daily')->info('Hasil Cek Validitas Search', [
            'ip' => $request->ip(),
            'type' => $type,
            'keyword' => $keyword,
            'found' => $result ? 'yes' : 'no',
            'timestamp' => now()->toDateTimeString()
        ]);

        if ($result) {
            // Redirect ke URL dengan UUID (lebih aman)
            return redirect()->route('cek_validitas.show', [
                'type' => $type,
                'uuid' => $result->uuid
            ]);
        }

        // Jika tidak ditemukan, kembali dengan error
        return redirect()->route('cek_validitas')
            ->with('error', 'Data tidak ditemukan. Mohon periksa kembali jenis lembaga dan nomor yang Anda masukkan.')
            ->withInput();
    }

    /**
     * Tampilkan detail berdasarkan UUID
     */
    public function show($type, $uuid)
    {
        $result = null;

        // Cari data berdasarkan UUID
        if ($type == 'majelis_taklim') {
            $result = \App\Models\Sktpiagammt::with(['kecamatan', 'kelurahan'])
                ->where('uuid', $uuid)
                ->first();
        } elseif ($type == 'masjid') {
            $result = \App\Models\SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid'])
                ->where('uuid', $uuid)
                ->first();
        } elseif ($type == 'mushalla') {
            $result = \App\Models\SktMushalla::with(['kecamatan', 'kelurahan', 'tipologiMushalla'])
                ->where('uuid', $uuid)
                ->first();
        } elseif ($type == 'marbot') {
            $result = \App\Models\Marbot::with(['kecamatan', 'kelurahan', 'masjid', 'mushalla'])
                ->where('uuid', $uuid)
                ->first();
        }

        // Jika tidak ditemukan, redirect ke form
        if (!$result) {
            return redirect()->route('cek_validitas')
                ->with('error', 'Data tidak ditemukan atau sudah tidak valid.');
        }

        // Log akses detail
        Log::channel('daily')->info('Cek Validitas Detail View', [
            'ip' => request()->ip(),
            'type' => $type,
            'uuid' => $uuid,
            'timestamp' => now()->toDateTimeString()
        ]);

        return view('frontend.cek_validitas_detail', compact('result', 'type'));
    }
}
