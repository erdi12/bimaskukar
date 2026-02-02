<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanans = Layanan::latest()->get();

        return view('backend.layanan.index', compact('layanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'ikon' => 'nullable|string|max:255', // text input
            'ikon_file' => 'nullable|file', // file input
            'deskripsi_singkat' => 'required|string',
            'konten' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->judul);

        // Prevent Stored XSS
        if (isset($validated['konten'])) {
            $validated['konten'] = strip_tags($validated['konten'], '<p><a><ul><ol><li><b><strong><i><em><br><h1><h2><h3><h4><h5><h6><table><thead><tbody><tr><th><td><img><div><span>');
        }

        // Logic upload file JSON
        if ($request->hasFile('ikon_file')) {
            $request->validate([
                'ikon_file' => 'mimetypes:application/json,text/plain|max:2048', // 2MB max
            ]);

            $file = $request->file('ikon_file');
            // Gunakan original name or unique name
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan di storage/app/public/layanan_icons
            $path = $file->storeAs('layanan_icons', $filename, 'public');
            
            // Simpan path relatif ke table (untuk asset('storage/...'))
            // Namun karena kita pakai asset('storage/...') nanti, kita bisa simpan path lengkap atau relatif.
            // Convention: simpan relative path dari disk public root (layanan_icons/filename.json)
            $validated['ikon'] = 'layanan_icons/' . $filename;
        } else {
             // Jika tidak ada file, gunakan input text
             // Hapus ikon_file dari validated array (jika ada) karena tidak masuk DB
        }

        Layanan::create($validated);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Layanan $layanan)
    {
        // Used for frontend later, or admin preview
        return view('backend.layanan.show', compact('layanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Layanan $layanan)
    {
        return view('backend.layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Layanan $layanan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'ikon' => 'nullable|string|max:255', // emoji, class, or URL
            'deskripsi_singkat' => 'required|string',
            'konten' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->judul);

        // Prevent Stored XSS
        if (isset($validated['konten'])) {
            $validated['konten'] = strip_tags($validated['konten'], '<p><a><ul><ol><li><b><strong><i><em><br><h1><h2><h3><h4><h5><h6><table><thead><tbody><tr><th><td><img><div><span>');
        }

        // Logic upload file JSON
        if ($request->hasFile('ikon_file')) {
            $request->validate([
                'ikon_file' => 'mimetypes:application/json,text/plain|max:2048',
            ]);

            // Hapus file lama jika ada dan itu adalah file lokal (bukan URL/FontAwesome)
            if ($layanan->ikon && \Illuminate\Support\Str::endsWith($layanan->ikon, '.json') && \Illuminate\Support\Facades\Storage::disk('public')->exists($layanan->ikon)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($layanan->ikon);
            }

            $file = $request->file('ikon_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('layanan_icons', $filename, 'public');
            
            $validated['ikon'] = 'layanan_icons/' . $filename;
        }

        $layanan->update($validated);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
