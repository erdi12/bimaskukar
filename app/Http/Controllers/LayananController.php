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
            'ikon' => 'nullable|string|max:50', // emoji or class
            'deskripsi_singkat' => 'required|string',
            'konten' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->judul);

        // Prevent Stored XSS
        if (isset($validated['konten'])) {
            $validated['konten'] = strip_tags($validated['konten'], '<p><a><ul><ol><li><b><strong><i><em><br><h1><h2><h3><h4><h5><h6><table><thead><tbody><tr><th><td><img><div><span>');
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
            'ikon' => 'nullable|string|max:50',
            'deskripsi_singkat' => 'required|string',
            'konten' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->judul);

        // Prevent Stored XSS
        if (isset($validated['konten'])) {
            $validated['konten'] = strip_tags($validated['konten'], '<p><a><ul><ol><li><b><strong><i><em><br><h1><h2><h3><h4><h5><h6><table><thead><tbody><tr><th><td><img><div><span>');
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
