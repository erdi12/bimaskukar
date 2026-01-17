<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KelurahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kelurahan::with('kecamatan')->orderBy('nama_kelurahan', 'asc');

        if ($request->filled('kecamatan_filter')) {
            $query->where('kecamatan_id', $request->kecamatan_filter);
        }

        $kelurahans = $query->get();
        $kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();

        return view('backend.kelurahan.index', compact('kelurahans', 'kecamatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();
        return view('backend.kelurahan.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama_kelurahan' => 'required|string|max:255|unique:kelurahans,nama_kelurahan',
            'jenis_kelurahan' => 'required|in:Desa,Kelurahan',
        ], [
            'kecamatan_id.required' => 'Kecamatan harus dipilih',
            'nama_kelurahan.required' => 'Nama Kelurahan harus diisi',
            'nama_kelurahan.unique' => 'Nama Kelurahan sudah terdaftar',
            'jenis_kelurahan.required' => 'Jenis Kelurahan harus dipilih',
            'jenis_kelurahan.in' => 'Jenis Kelurahan tidak valid',
        ]);

        Kelurahan::create($request->only('kecamatan_id', 'nama_kelurahan', 'jenis_kelurahan'));

        Alert::success('Berhasil', 'Data Kelurahan berhasil ditambahkan');
        return redirect()->route('kelurahan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kelurahan = Kelurahan::with('kecamatan')->findOrFail($id);
        return view('backend.kelurahan.show', compact('kelurahan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();
        return view('backend.kelurahan.edit', compact('kelurahan', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);

        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama_kelurahan' => 'required|string|max:255|unique:kelurahans,nama_kelurahan,' . $id,
            'jenis_kelurahan' => 'required|in:Desa,Kelurahan',
        ], [
            'kecamatan_id.required' => 'Kecamatan harus dipilih',
            'nama_kelurahan.required' => 'Nama Kelurahan harus diisi',
            'nama_kelurahan.unique' => 'Nama Kelurahan sudah terdaftar',
            'jenis_kelurahan.required' => 'Jenis Kelurahan harus dipilih',
            'jenis_kelurahan.in' => 'Jenis Kelurahan tidak valid',
        ]);

        $kelurahan->update($request->only('kecamatan_id', 'nama_kelurahan', 'jenis_kelurahan'));

        Alert::success('Berhasil', 'Data Kelurahan berhasil diperbarui');
        return redirect()->route('kelurahan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kelurahan->delete();

        Alert::success('Berhasil', 'Data Kelurahan berhasil dihapus');
        return redirect()->route('kelurahan.index');
    }
}
