<?php

namespace App\Http\Controllers;

use App\Models\PengajuanBerkas;
use App\Models\SeleksiBerkas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeleksiBerkasController extends Controller
{
    /**
     * Daftar semua seleksi berkas.
     */
    public function index()
    {
        $seleksis = SeleksiBerkas::withCount('pengajuans')->latest()->get();

        return view('backend.seleksi_berkas.index', compact('seleksis'));
    }

    /**
     * Form buat seleksi baru.
     */
    public function create()
    {
        return view('backend.seleksi_berkas.create');
    }

    /**
     * Simpan seleksi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'tanggal_buka'   => 'nullable|date',
            'tanggal_tutup'  => 'nullable|date|after_or_equal:tanggal_buka',
            'is_active'      => 'boolean',
            'field_configs'  => 'nullable|string', // JSON string dari form builder
            'berkas_configs' => 'nullable|string', // JSON string dari berkas builder
        ]);

        // Parse JSON configs
        $fieldConfigs  = $request->input('field_configs')  ? json_decode($request->input('field_configs'), true)  : [];
        $berkasConfigs = $request->input('berkas_configs') ? json_decode($request->input('berkas_configs'), true) : [];

        $slug = Str::slug($validated['judul']);
        $originalSlug = $slug;
        $count = 1;
        while (SeleksiBerkas::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        SeleksiBerkas::create([
            'judul'          => $validated['judul'],
            'slug'           => $slug,
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'field_configs'  => $fieldConfigs,
            'berkas_configs' => $berkasConfigs,
            'tanggal_buka'   => $validated['tanggal_buka'] ?? null,
            'tanggal_tutup'  => $validated['tanggal_tutup'] ?? null,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        return redirect()->route('seleksi_berkas.index')
            ->with('success', 'Seleksi berkas "' . $validated['judul'] . '" berhasil dibuat.');
    }

    /**
     * Form edit seleksi berkas.
     */
    public function edit(SeleksiBerkas $seleksi_berkas)
    {
        return view('backend.seleksi_berkas.edit', ['seleksi' => $seleksi_berkas]);
    }

    /**
     * Update seleksi berkas.
     */
    public function update(Request $request, SeleksiBerkas $seleksi_berkas)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'tanggal_buka'   => 'nullable|date',
            'tanggal_tutup'  => 'nullable|date|after_or_equal:tanggal_buka',
            'is_active'      => 'boolean',
            'field_configs'  => 'nullable|string',
            'berkas_configs' => 'nullable|string',
        ]);

        $fieldConfigs  = $request->input('field_configs')  ? json_decode($request->input('field_configs'), true)  : [];
        $berkasConfigs = $request->input('berkas_configs') ? json_decode($request->input('berkas_configs'), true) : [];

        // Regenerate slug jika judul berubah
        if ($seleksi_berkas->judul !== $validated['judul']) {
            $slug = Str::slug($validated['judul']);
            $originalSlug = $slug;
            $count = 1;
            while (SeleksiBerkas::where('slug', $slug)->where('id', '!=', $seleksi_berkas->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
        } else {
            $slug = $seleksi_berkas->slug;
        }

        $seleksi_berkas->update([
            'judul'          => $validated['judul'],
            'slug'           => $slug,
            'deskripsi'      => $validated['deskripsi'] ?? null,
            'field_configs'  => $fieldConfigs,
            'berkas_configs' => $berkasConfigs,
            'tanggal_buka'   => $validated['tanggal_buka'] ?? null,
            'tanggal_tutup'  => $validated['tanggal_tutup'] ?? null,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        return redirect()->route('seleksi_berkas.index')
            ->with('success', 'Seleksi berkas berhasil diperbarui.');
    }

    /**
     * Hapus seleksi berkas.
     */
    public function destroy(SeleksiBerkas $seleksi_berkas)
    {
        $seleksi_berkas->delete();

        return redirect()->route('seleksi_berkas.index')
            ->with('success', 'Seleksi berkas berhasil dihapus.');
    }

    /**
     * Daftar semua pengajuan untuk satu seleksi.
     */
    public function pengajuan(SeleksiBerkas $seleksi_berkas)
    {
        $seleksi    = $seleksi_berkas;
        $pengajuans = PengajuanBerkas::where('seleksi_berkas_id', $seleksi->id)
            ->latest()
            ->get();

        return view('backend.seleksi_berkas.pengajuan', compact('seleksi', 'pengajuans'));
    }

    /**
     * Detail satu pengajuan berkas.
     */
    public function showPengajuan(SeleksiBerkas $seleksi_berkas, PengajuanBerkas $pengajuan)
    {
        return view('backend.seleksi_berkas.show_pengajuan', [
            'seleksi'   => $seleksi_berkas,
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Update status pengajuan dari admin.
     */
    public function updateStatus(Request $request, PengajuanBerkas $pengajuan)
    {
        $request->validate([
            'status'        => 'required|in:menunggu,diproses,diterima,ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $pengajuan->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
