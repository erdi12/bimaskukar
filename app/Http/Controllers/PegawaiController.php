<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pegawai;
use RealRashid\SweetAlert\Facades\Alert;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawais = Pegawai::orderBy('urutan', 'asc')->get();
        return view('backend.pegawai.index', compact('pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sambutan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        // Handle is_kepala
        $is_kepala = $request->has('is_kepala');
        if ($is_kepala) {
             // Reset yang lain jadi false
             Pegawai::where('is_kepala', true)->update(['is_kepala' => false]);
             $validated['is_kepala'] = true;
        } else {
            $validated['is_kepala'] = false;
        }

        // Handle Upload Foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $file->move(public_path('uploads/pegawai'), $filename);
            $validated['foto'] = $filename;
        }

        Pegawai::create($validated);

        Alert::success('Berhasil', 'Data Pegawai berhasil ditambahkan.');
        return redirect()->route('pegawai.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('backend.pegawai.edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sambutan' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        // Handle is_kepala
        $is_kepala = $request->has('is_kepala');
        if ($is_kepala) {
             // Reset yang lain jadi false, kecuali diri sendiri (redundant tapi aman)
             Pegawai::where('id', '!=', $id)->where('is_kepala', true)->update(['is_kepala' => false]);
             $validated['is_kepala'] = true;
        } else {
            // Jika sebelumnya kepala, dan sekarang diuncheck, maka tidak ada kepala.
            $validated['is_kepala'] = false;
        }

        // Handle Upload Foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($pegawai->foto && file_exists(public_path('uploads/pegawai/' . $pegawai->foto))) {
                unlink(public_path('uploads/pegawai/' . $pegawai->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $file->move(public_path('uploads/pegawai'), $filename);
            $validated['foto'] = $filename;
        }

        $pegawai->update($validated);

        Alert::success('Berhasil', 'Data Pegawai berhasil diperbarui.');
        return redirect()->route('pegawai.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        
        // Hapus foto
        if ($pegawai->foto && file_exists(public_path('uploads/pegawai/' . $pegawai->foto))) {
            unlink(public_path('uploads/pegawai/' . $pegawai->foto));
        }

        $pegawai->delete();

        Alert::success('Berhasil', 'Data Pegawai berhasil dihapus.');
        return redirect()->route('pegawai.index');
    }
}
