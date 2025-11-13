<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KecamatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();
        return view('backend.kecamatan.index', compact('kecamatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.kecamatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string|max:255|unique:kecamatans,kecamatan',
        ], [
            'kecamatan.required' => 'Nama Kecamatan harus diisi',
            'kecamatan.unique' => 'Nama Kecamatan sudah terdaftar',
        ]);

        Kecamatan::create($request->only('kecamatan'));

        Alert::success('Berhasil', 'Data Kecamatan berhasil ditambahkan');
        return redirect()->route('kecamatan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return view('backend.kecamatan.show', compact('kecamatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        return view('backend.kecamatan.edit', compact('kecamatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);

        $request->validate([
            'kecamatan' => 'required|string|max:255|unique:kecamatans,kecamatan,' . $id,
        ], [
            'kecamatan.required' => 'Nama Kecamatan harus diisi',
            'kecamatan.unique' => 'Nama Kecamatan sudah terdaftar',
        ]);

        $kecamatan->update($request->only('kecamatan'));

        Alert::success('Berhasil', 'Data Kecamatan berhasil diperbarui');
        return redirect()->route('kecamatan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        Alert::success('Berhasil', 'Data Kecamatan berhasil dihapus');
        return redirect()->route('kecamatan.index');
    }
}
