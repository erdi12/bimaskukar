<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sktpiagammt;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DOMPDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Browsershot\Browsershot;
use Yajra\DataTables\Facades\DataTables;

class SktpiagammtController extends Controller
{
    private const UPLOAD_PATH = [
        'skt' => 'app/public/skt',
        'piagam' => 'app/public/piagam',
        'berkas' => 'app/public/berkas',
    ];

    /**
     * Handle file upload for SKT, Piagam, and Berkas
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $type
     * @param  string|null  $oldFile
     * @return string
     */
    private function handleFileUpload($file, $type, $oldFile = null)
    {
        // Hapus file lama jika ada
        if ($oldFile && file_exists(storage_path(self::UPLOAD_PATH[$type].'/'.$oldFile))) {
            unlink(storage_path(self::UPLOAD_PATH[$type].'/'.$oldFile));
        }

        // Sanitasi dan generate nama file baru
        $fileName = time().'_'.preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());

        // Pastikan direktori ada
        $path = storage_path(self::UPLOAD_PATH[$type]);
        if (! file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Upload file
        $file->move($path, $fileName);

        return $fileName;
    }

    public function index()
    {
        // Ambil data yang diperlukan
        $kecamatans = \App\Models\Kecamatan::all();
        $kelurahans = collect(); // Default kosong, akan diisi saat filter

        // Query dasar
        $query = \App\Models\Sktpiagammt::with(['kecamatan', 'kelurahan']);

        // Filter berdasarkan request
        if (request()->has('kecamatan_id') && request('kecamatan_id')) {
            $query->where('kecamatan_id', request('kecamatan_id'));
            // Load kelurahan untuk dropdown
            $kelurahans = \App\Models\Kelurahan::where('kecamatan_id', request('kecamatan_id'))->get();
        }

        if (request()->has('kelurahan_id') && request('kelurahan_id')) {
            $query->where('kelurahan_id', request('kelurahan_id'));
        }

        // Ambil semua data
        $sktpiagammts = $query->orderBy('mendaftar', 'desc')->get();

        return view('backend.skt_piagam_mt.index', compact('sktpiagammts', 'kecamatans', 'kelurahans'));
    }

    // public function index(Request $request)
    // {
    //     try {
    //         // Ambil data yang diperlukan untuk filter dropdown
    //         $kecamatans = Kecamatan::all();
    //         $kelurahans = collect();

    //         // Load kelurahan jika ada filter kecamatan
    //         if ($request->has('kecamatan_id') && $request->kecamatan_id) {
    //             $kelurahans = Kelurahan::where('kecamatan_id', $request->kecamatan_id)->get();
    //         }

    //         if ($request->ajax()) {
    //             $query = Sktpiagammt::with(['kecamatan', 'kelurahan']);

    //             // Apply filters
    //             if ($request->has('kecamatan_id') && $request->kecamatan_id) {
    //                 $query->where('kecamatan_id', $request->kecamatan_id);
    //             }
    //             if ($request->has('kelurahan_id') && $request->kelurahan_id) {
    //                 $query->where('kelurahan_id', $request->kelurahan_id);
    //             }

    //             return DataTables::of($query)
    //                 ->addIndexColumn()
    //                 ->addColumn('status_badge', function($row) {
    //                     if ($row->status == 'aktif') {
    //                         return '<span class="badge bg-success">Aktif</span>';
    //                     } elseif ($row->status == 'nonaktif') {
    //                         return '<span class="badge bg-danger">Non-Aktif</span>';
    //                     } else {
    //                         return '<span class="badge bg-warning">Belum Update</span>';
    //                     }
    //                 })
    //                 ->addColumn('action', function($row) {
    //                     $actionBtn = '<div class="btn-group btn-group-sm mb-2" role="group">';
    //                     $actionBtn .= '<button type="button" class="btn btn-success d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#wilayahModal' . $row->id . '">';
    //                     $actionBtn .= '<i class="fa-regular fa-file-lines me-1"></i> Cetak SKT</button>';

    //                     $actionBtn .= '<a href="' . route('skt_piagam_mt.cetak_piagam', $row->id) . '" class="btn btn-warning d-inline-flex align-items-center" target="_blank">';
    //                     $actionBtn .= '<i class="fa-regular fa-file-lines me-1"></i> Cetak Piagam</a>';
    //                     $actionBtn .= '</div>';

    //                     $actionBtn .= '<div class="btn-group btn-group-sm" role="group">';
    //                     $actionBtn .= '<a href="' . route('skt_piagam_mt.edit', $row->id) . '" class="btn btn-success d-inline-flex align-items-center">';
    //                     $actionBtn .= '<i class="fa-regular fa-pen-to-square me-1"></i> Edit</a>';

    //                     $actionBtn .= '<button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="confirmDelete(' . $row->id . ')">';
    //                     $actionBtn .= '<i class="fa-regular fa-trash-can me-1"></i> Hapus</button>';
    //                     $actionBtn .= '</div>';

    //                     return $actionBtn;
    //                 })
    //                 ->addColumn('documents', function($row) {
    //                     $docsBtn = '<div class="btn-group btn-group-sm mb-2 text-nowrap">';

    //                     // SKT Button
    //                     if (!$row->file_skt) {
    //                         $docsBtn .= '<button type="button" class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#uploadSktModal' . $row->id . '">';
    //                         $docsBtn .= '<i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Upload SKT</button>';
    //                     } else {
    //                         $docsBtn .= '<a href="' . asset('storage/skt/' . $row->file_skt) . '" class="btn btn-success d-inline-flex align-items-center" target="_blank">';
    //                         $docsBtn .= '<i class="fa-regular fa-eye me-1"></i> Lihat SKT</a>';

    //                         $docsBtn .= '<button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="confirmDeleteSkt(' . $row->id . ')">';
    //                         $docsBtn .= '<i class="fa-regular fa-trash-can me-1"></i> Hapus SKT</button>';
    //                     }
    //                     $docsBtn .= '</div>';

    //                     // Piagam Button
    //                     $docsBtn .= '<div class="btn-group btn-group-sm text-nowrap">';
    //                     if (!$row->file_piagam) {
    //                         $docsBtn .= '<button type="button" class="btn btn-info d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#uploadPiagamModal' . $row->id . '">';
    //                         $docsBtn .= '<i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Upload Piagam</button>';
    //                     } else {
    //                         $docsBtn .= '<a href="' . asset('storage/piagam/' . $row->file_piagam) . '" class="btn btn-success d-inline-flex align-items-center" target="_blank">';
    //                         $docsBtn .= '<i class="fa-regular fa-eye me-1"></i> Lihat Piagam</a>';

    //                         $docsBtn .= '<button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="confirmDeletePiagam(' . $row->id . ')">';
    //                         $docsBtn .= '<i class="fa-regular fa-trash-can me-1"></i> Hapus Piagam</button>';
    //                     }
    //                     $docsBtn .= '</div>';

    //                     return $docsBtn;
    //                 })
    //                 ->addColumn('berkas', function($row) {
    //                     $berkasBtn = '<div class="btn-group btn-group-sm">';
    //                     if (!$row->file_berkas) {
    //                         $berkasBtn .= '<button type="button" class="btn btn-info d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#uploadBerkasModal' . $row->id . '">';
    //                         $berkasBtn .= '<i class="fa-solid fa-arrow-up-from-bracket me-1"></i> Upload Berkas</button>';
    //                     } else {
    //                         $berkasBtn .= '<a href="' . asset('storage/berkas/' . $row->file_berkas) . '" class="btn btn-success d-inline-flex align-items-center" target="_blank">';
    //                         $berkasBtn .= '<i class="fa-regular fa-eye me-1"></i> Lihat Berkas</a>';

    //                         $berkasBtn .= '<button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="confirmDeleteBerkas(' . $row->id . ')">';
    //                         $berkasBtn .= '<i class="fa-regular fa-trash-can me-1"></i> Hapus Berkas</button>';
    //                     }
    //                     $berkasBtn .= '</div>';
    //                     return $berkasBtn;
    //                 })
    //                 ->rawColumns(['action', 'status_badge', 'documents', 'berkas'])
    //                 ->make(true);
    //         }

    //         return view('backend.skt_piagam_mt.index', compact('kecamatans', 'kelurahans'));
    //     } catch (\Exception $e) {
    //         Log::error('DataTables Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Terjadi kesalahan saat memuat data'], 500);
    //     }
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatans = Kecamatan::all();

        return view('backend.skt_piagam_mt.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Cek apakah nomor statistik sudah ada di database
        $existingNomorStatistik = Sktpiagammt::where('nomor_statistik', $request->nomor_statistik)->first();
        if ($existingNomorStatistik) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['nomor_statistik' => 'Nomor Statistik Sudah Ada']);
        }

        $validated = $request->validate([
            'nomor_statistik' => 'required|unique:sktpiagammts,nomor_statistik',
            'nama_majelis' => 'required',
            'alamat' => 'required',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'tanggal_berdiri' => 'required|date',
            'status' => 'required|in:aktif,nonaktif,belum_update',
            'ketua' => 'required',
            'no_hp' => 'required',
            'mendaftar' => 'required|date',
            'mendaftar_ulang' => 'required|date',
        ]);

        Sktpiagammt::create($validated);

        Alert::success('Success', 'Data Majelis Ta\'lim berhasil ditambahkan.');

        return redirect()->route('skt_piagam_mt_v2.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sktpiagammt = Sktpiagammt::with(['kecamatan', 'kelurahan'])->findOrFail($id);

        return view('backend.skt_piagam_mt.show', compact('sktpiagammt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sktpiagammt = Sktpiagammt::findOrFail($id);
        $kecamatans = Kecamatan::all();

        return view('backend.skt_piagam_mt.edit', compact('sktpiagammt', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sktpiagammt = Sktpiagammt::findOrFail($id);

        $validated = $request->validate([
            'nomor_statistik' => 'required|unique:sktpiagammts,nomor_statistik,'.$id,
            'nama_majelis' => 'required',
            'alamat' => 'required',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'tanggal_berdiri' => 'required|date',
            'status' => 'required|in:aktif,nonaktif,belum_update',
            'ketua' => 'required',
            'no_hp' => 'required',
            'mendaftar' => 'required|date',
            'mendaftar_ulang' => 'required|date',
        ]);

        $sktpiagammt->update($validated);

        Alert::success('Success', 'Data Majelis Ta\'lim berhasil diperbarui.');

        return redirect()->route('skt_piagam_mt_v2.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $sktpiagammt = Sktpiagammt::findOrFail($id);
            $nama = $sktpiagammt->nama_majelis;

            // Hapus file SKT jika ada
            if ($sktpiagammt->file_skt && file_exists(storage_path('app/public/skt/'.$sktpiagammt->file_skt))) {
                unlink(storage_path('app/public/skt/'.$sktpiagammt->file_skt));
            }

            // Hapus file Piagam jika ada
            if ($sktpiagammt->file_piagam && file_exists(storage_path('app/public/piagam/'.$sktpiagammt->file_piagam))) {
                unlink(storage_path('app/public/piagam/'.$sktpiagammt->file_piagam));
            }

            // Hapus file Berkas jika ada
            if ($sktpiagammt->file_berkas && file_exists(storage_path('app/public/berkas/'.$sktpiagammt->file_berkas))) {
                unlink(storage_path('app/public/berkas/'.$sktpiagammt->file_berkas));
            }

            $sktpiagammt->delete();

            // Menggunakan Alert facade untuk menampilkan notifikasi sukses
            Alert::success('Berhasil', "Data Majelis Ta'lim '$nama' berhasil dihapus.");

            return redirect()->route('skt_piagam_mt_v2.index');
        } catch (\Exception $e) {
            // Menggunakan Alert facade untuk menampilkan notifikasi error
            Alert::error('Gagal', 'Gagal menghapus data. '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.index');
        }
    }

    public function cetakPiagam($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::with(['kecamatan', 'kelurahan'])->findOrFail($id);

            // Catat aktivitas cetak Piagam
            activity()
                ->performedOn($sktpiagammt)
                ->causedBy(auth()->user()) // Bisa null jika guest, tapi route ini biasanya dilindungi auth atau kita bisa cek auth()->check()
                ->event('cetak_piagam')
                ->log('Mencetak Piagam Majelis Taklim');

            // Debug: lihat struktur data
            \Log::info('Data Sktpiagammt:', $sktpiagammt->toArray());

            $logoPath = public_path('images/kemenag/kemenag.png');
            $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

            // Generate PDF menggunakan DomPDF
            $pdf = Pdf::loadView('backend.skt_piagam_mt.cetak_piagam', compact('sktpiagammt', 'logoBase64'))
                ->setPaper('A4')
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('chroot', public_path());

            // Register custom font ke DomPDF
            $fontPath = public_path('fonts/imprint-mt-shadow.ttf');
            if (file_exists($fontPath)) {
                $pdf->getDomPDF()->getOptions()->set('fontDir', public_path('fonts'));
                $pdf->getDomPDF()->getOptions()->set('fontCache', storage_path('logs'));
            }

            // Buat nama file yang sesuai format: "nomor_statistik - nama_majelis - Piagam"
            $fileName = $sktpiagammt->nomor_statistik.' - '.$sktpiagammt->nama_majelis.' - Piagam.pdf';
            // Bersihkan karakter yang tidak valid untuk nama file
            $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $fileName);

            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            // Log error
            \Log::error('PDF Generation Error: '.$e->getMessage());

            // Tampilkan pesan error yang lebih informatif
            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF: '.$e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    // public function cetakPiagam($id)
    // {
    //     try {
    //         $sktpiagammt = Sktpiagammt::findOrFail($id);
    //         $logoPath = public_path('images/kemenag/kemenag.png');
    //         $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    //         // di Controller
    //         $fontPath = public_path('fonts/imprint-mt-shadow.ttf');
    //         $fontData = base64_encode(file_get_contents($fontPath));

    //         // Tambahkan variabel yang diperlukan untuk template

    //         // Render view ke HTML dengan semua variabel yang diperlukan
    //         $html = view('backend.skt_piagam_mt.cetak_piagam', compact(
    //             'sktpiagammt', 'logoBase64', 'fontData'))->render();

    //         // Simpan HTML ke file temporary dengan path absolut
    //         $tempPath = storage_path('app/public/temp');
    //         if (!file_exists($tempPath)) {
    //             mkdir($tempPath, 0755, true);
    //         }

    //         $tempFile = $tempPath . '/skt_' . time() . '.html';
    //         file_put_contents($tempFile, $html);

    //         // Generate PDF dengan Browsershot dengan konfigurasi lebih detail
    //         $pdf = Browsershot::html(file_get_contents($tempFile))
    //             ->format('A4')
    //             ->margins(10, 10, 10, 10) // Tambahkan margin untuk mencegah konten menyentuh bingkai
    //             ->showBackground()
    //             ->waitUntilNetworkIdle()
    //             ->timeout(120)
    //             ->pdf();

    //         // Hapus file temporary
    //         @unlink($tempFile);

    //         // Kembalikan respons PDF dengan header yang benar
    //         // Buat nama file yang sesuai format: "nomor_statistik - nama_majelis - SKT"
    //         $fileName = $sktpiagammt->nomor_statistik . ' - ' . $sktpiagammt->nama_majelis . ' - Piagam.pdf';
    //         // Bersihkan karakter yang tidak valid untuk nama file
    //         $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $fileName);

    //         return response($pdf)
    //             ->header('Content-Type', 'application/pdf')
    //             ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');

    //     } catch (\Exception $e) {
    //         // Log error
    //         \Log::error('PDF Generation Error: ' . $e->getMessage());

    //         // Tampilkan pesan error yang lebih informatif
    //         return response()->view('errors.custom', [
    //             'message' => 'Gagal membuat PDF: ' . $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ], 500);
    //     }
    // }

    public function cetakSkt($id, Request $request)
    {
        try {
            $sktpiagammt = Sktpiagammt::with(['kecamatan', 'kelurahan'])->findOrFail($id);

            // Catat aktivitas cetak SKT
            activity()
                ->performedOn($sktpiagammt)
                ->causedBy(auth()->user())
                ->event('cetak_skt')
                ->log('Mencetak SKT Majelis Taklim');

            $logoPath = public_path('images/kemenag/kemenag.png');
            $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

            // Generate PDF menggunakan DomPDF
            $pdf = Pdf::loadView('backend.skt_piagam_mt.cetak_skt', [
                'sktpiagammt' => $sktpiagammt,
                'logoBase64' => $logoBase64,
            ])
                ->setPaper('A4')
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('chroot', public_path());

            // Register custom font ke DomPDF
            $fontPath = public_path('fonts/imprint-mt-shadow.ttf');
            if (file_exists($fontPath)) {
                $pdf->getDomPDF()->getOptions()->set('fontDir', public_path('fonts'));
                $pdf->getDomPDF()->getOptions()->set('fontCache', storage_path('logs'));
            }

            // Buat nama file yang sesuai format: "nomor_statistik - nama_majelis - SKT"
            $fileName = $sktpiagammt->nomor_statistik.' - '.$sktpiagammt->nama_majelis.' - SKT.pdf';
            // Bersihkan karakter yang tidak valid untuk nama file
            $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $fileName);

            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            // Log error
            \Log::error('PDF Generation Error: '.$e->getMessage());

            // Tampilkan pesan error yang lebih informatif
            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF: '.$e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Mendapatkan kelurahan berdasarkan kecamatan
     */
    public function getKelurahan(Request $request)
    {
        try {
            $kecamatanId = $request->get('kecamatan_id');

            if (! $kecamatanId) {
                return response()->json(['error' => 'ID Kecamatan tidak ditemukan'], 400);
            }

            // Ambil kelurahan berdasarkan kecamatan_id
            $kelurahans = \App\Models\Kelurahan::where('kecamatan_id', $kecamatanId)
                ->select('id', 'nama_kelurahan') // hanya ambil kolom yang diperlukan
                ->orderBy('nama_kelurahan')
                ->get();

            return response()->json($kelurahans);
        } catch (\Exception $e) {
            \Log::error('Error getKelurahan: '.$e->getMessage());

            return response()->json(['error' => 'Gagal mengambil data kelurahan'], 500);
        }
    }

    /**
     * Mendapatkan nomor statistik berikutnya berdasarkan kecamatan
     */
    public function getNextNomorStatistik(Request $request)
    {
        try {
            $kecamatanId = $request->kecamatan_id;

            if (! $kecamatanId) {
                return response()->json(['error' => 'ID Kecamatan tidak ditemukan'], 400);
            }

            // Ambil data kecamatan
            $kecamatan = \App\Models\Kecamatan::findOrFail($kecamatanId);

            // Normalisasi nama kecamatan biar cocok sama config
            $namaKecamatan = strtolower(trim($kecamatan->kecamatan));

            // Ambil kode kecamatan dari file konfigurasi
            $kodeKecamatanConfig = config('nomor_statistik.'.$namaKecamatan);

            if (! $kodeKecamatanConfig) {
                // Fallback jika tidak ada di konfigurasi
                $kodeKecamatan = str_pad($kecamatan->id, 2, '0', STR_PAD_LEFT);
                $kodeKecamatanPrefix = "431.2.64.02.{$kodeKecamatan}";
                \Log::warning("Kode kecamatan untuk '{$namaKecamatan}' tidak ditemukan di konfigurasi. Menggunakan fallback: {$kodeKecamatanPrefix}");
            } else {
                $kodeKecamatanPrefix = $kodeKecamatanConfig;
            }

            // Cari nomor statistik terakhir untuk kecamatan ini
            $lastNumber = \App\Models\Sktpiagammt::where('nomor_statistik', 'like', "{$kodeKecamatanPrefix}.%")
                ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_statistik, ".", -1) AS UNSIGNED) DESC')
                ->value('nomor_statistik');

            if ($lastNumber) {
                // Ambil angka terakhir dan tambahkan 1
                $parts = explode('.', $lastNumber);
                $lastPart = end($parts);
                $nextNumber = str_pad((int) $lastPart + 1, 3, '0', STR_PAD_LEFT);
            } else {
                // Jika belum ada nomor untuk kecamatan ini, mulai dari 001
                $nextNumber = '001';
            }

            // Format nomor statistik lengkap
            $nomorStatistik = "{$kodeKecamatanPrefix}.{$nextNumber}";

            return response()->json(['nomor_statistik' => $nomorStatistik]);
        } catch (\Exception $e) {
            \Log::error('Error generating nomor_statistik: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Import data dari file Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240', // max 10MB
        ]);

        try {
            $file = $request->file('excel_file');

            // Import data menggunakan Laravel Excel
            Excel::import(new \App\Imports\SktpiagammtImport, $file);

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', 'Data Majelis Ta\'lim berhasil diimport.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessage = 'Terjadi kesalahan pada: ';

            foreach ($failures as $failure) {
                $value = $failure->values()[$failure->attribute()] ?? 'tidak ada nilai';
                $errorMessage .= "\nBaris ".$failure->row().' (kolom: '.$failure->attribute().'): ';
                $errorMessage .= $failure->errors()[0] ?? 'Error tidak diketahui';
                if ($failure->attribute() == 'nomor_statistik' && isset($failure->values()['nomor_statistik'])) {
                    $errorMessage .= ' (Nomor Statistik: '.$failure->values()['nomor_statistik'].')';
                }
            }

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Gagal import data. '.$errorMessage);
        } catch (\Exception $e) {
            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Gagal import data. '.$e->getMessage());
        }
    }

    /**
     * Download template Excel untuk import data
     */
    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_import_mt.xlsx');

        // Jika file template belum ada, buat template baru
        if (! file_exists($filePath)) {
            // Buat direktori jika belum ada
            if (! file_exists(public_path('templates'))) {
                mkdir(public_path('templates'), 0755, true);
            }

            // Buat template Excel baru
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
            $sheet = $spreadsheet->getActiveSheet();

            // Header kolom
            $headers = [
                'Nomor Statistik', 'Nama Majelis', 'Alamat', 'Kecamatan ID',
                'Kelurahan ID', 'Tanggal Berdiri (YYYY-MM-DD)', 'Status (aktif/nonaktif/belum_update)',
                'Ketua', 'No HP', 'Tanggal Mendaftar (YYYY-MM-DD)',
                'Tanggal Mendaftar Ulang (YYYY-MM-DD)',
            ];

            // Tulis header
            foreach ($headers as $index => $header) {
                $sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
            }

            // Contoh data
            $exampleData = [
                '431.2.64.02.01.001', 'Majelis Ta\'lim Al-Ikhlas', 'Jl. Contoh No. 123', '1',
                '1', '2020-01-01', 'aktif', 'Ahmad', '08123456789', '2020-01-15', '2023-01-15',
            ];

            // Tulis contoh data
            foreach ($exampleData as $index => $value) {
                $sheet->setCellValueByColumnAndRow($index + 1, 2, $value);
            }

            // Style header
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'CCCCCC'],
                ],
            ];
            $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

            // Auto size kolom
            foreach (range('A', 'K') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Simpan file
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);
        }

        return response()->download($filePath, 'template_import_majelis_talim.xlsx');
    }

    /**
     * Upload file berkas majelis ta'lim
     */
    public function uploadBerkas(Request $request)
    {
        $request->validate([
            'berkas_id' => 'required|exists:sktpiagammts,id',
            'berkas_file' => 'required|file|mimes:pdf|max:5120', // max 5MB, hanya PDF
        ]);

        try {
            $sktpiagammt = Sktpiagammt::findOrFail($request->berkas_id);

            // Upload file menggunakan helper method
            $fileName = $this->handleFileUpload(
                $request->file('berkas_file'),
                'berkas',
                $sktpiagammt->file_berkas
            );

            // Update database
            $sktpiagammt->file_berkas = $fileName;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', 'Berkas Majelis Ta\'lim berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Upload Berkas Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Terjadi kesalahan saat upload berkas. Silakan coba lagi.');
        }
    }

    /**
     * Upload file SKT
     */
    public function uploadSkt(Request $request)
    {
        $request->validate([
            'skt_id' => 'required|exists:sktpiagammts,id',
            'skt_file' => 'required|file|mimes:pdf|max:5120', // max 5MB, hanya PDF
        ]);

        try {
            $sktpiagammt = Sktpiagammt::findOrFail($request->skt_id);

            // Upload file menggunakan helper method
            $fileName = $this->handleFileUpload(
                $request->file('skt_file'),
                'skt',
                $sktpiagammt->file_skt
            );

            // Update database
            $sktpiagammt->file_skt = $fileName;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', 'File SKT berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Upload SKT Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Terjadi kesalahan saat upload file SKT. Silakan coba lagi.');
        }
    }

    /**
     * Upload file Piagam
     */
    public function uploadPiagam(Request $request)
    {
        $request->validate([
            'piagam_id' => 'required|exists:sktpiagammts,id',
            'piagam_file' => 'required|file|mimes:pdf|max:5120', // max 5MB, hanya PDF
        ]);

        try {
            $sktpiagammt = Sktpiagammt::findOrFail($request->piagam_id);

            // Upload file menggunakan helper method
            $fileName = $this->handleFileUpload(
                $request->file('piagam_file'),
                'piagam',
                $sktpiagammt->file_piagam
            );

            // Update database
            $sktpiagammt->file_piagam = $fileName;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', 'File Piagam berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Upload Piagam Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Terjadi kesalahan saat upload piagam. Silakan coba lagi.');
        }
    }

    /**
     * Hapus file SKT
     */
    public function deleteSkt($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::findOrFail($id);

            // Hapus file jika ada
            if ($sktpiagammt->file_skt && file_exists(storage_path(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt))) {
                unlink(storage_path(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt));
            }

            // Update database
            $sktpiagammt->file_skt = null;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', 'File SKT berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Delete SKT Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Terjadi kesalahan saat menghapus file SKT. Silakan coba lagi.');
        }
    }

    /**
     * Hapus file Piagam
     */
    public function deletePiagam($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::findOrFail($id);

            // Hapus file jika ada
            if ($sktpiagammt->file_piagam && file_exists(storage_path(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam))) {
                unlink(storage_path(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam));
            }

            // Update database
            $sktpiagammt->file_piagam = null;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', 'File Piagam berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Delete Piagam Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Terjadi kesalahan saat menghapus file Piagam. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan rekapan data Majelis Ta'lim
     */
    public function rekap()
    {
        // Ambil semua data Majelis Ta'lim dengan relasi
        $sktpiagammts = Sktpiagammt::with(['kecamatan', 'kelurahan'])->get();

        // Hitung total berdasarkan status
        $totalAktif = $sktpiagammts->where('status', 'aktif')->count();
        $totalNonaktif = $sktpiagammts->where('status', 'nonaktif')->count();
        $totalBelumUpdate = $sktpiagammts->where('status', 'belum_update')->count();

        // Hitung total berdasarkan kecamatan
        $totalPerKecamatan = $sktpiagammts->groupBy('kecamatan.kecamatan')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'aktif' => $items->where('status', 'aktif')->count(),
                    'nonaktif' => $items->where('status', 'nonaktif')->count(),
                    'belum_update' => $items->where('status', 'belum_update')->count(),
                ];
            });

        return view('backend.skt_piagam_mt.rekap', compact(
            'sktpiagammts',
            'totalAktif',
            'totalNonaktif',
            'totalBelumUpdate',
            'totalPerKecamatan'
        ));
    }

    /**
     * Export data ke Excel
     */
    public function export()
    {
        return Excel::download(new \App\Exports\SktpiagammtExport, 'majelis_talim.xlsx');
    }

    /**
     * Hapus file Berkas
     */
    public function deleteBerkas($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::findOrFail($id);

            // Hapus file jika ada
            if ($sktpiagammt->file_berkas && file_exists(storage_path(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas))) {
                unlink(storage_path(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas));
            }

            // Update database
            $sktpiagammt->file_berkas = null;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', 'Berkas Majelis Ta\'lim berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Delete Berkas Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Terjadi kesalahan saat menghapus berkas. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan data majelis taklim yang sudah dihapus
     */
    public function trash()
    {
        $trashedData = Sktpiagammt::onlyTrashed()
            ->with(['kecamatan', 'kelurahan'])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('backend.skt_piagam_mt.trash', compact('trashedData'));
    }

    /**
     * Memulihkan data majelis taklim yang sudah dihapus
     */
    public function restore($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::withTrashed()->findOrFail($id);
            $nama = $sktpiagammt->nama_majelis;

            $sktpiagammt->restore();

            Alert::success('Berhasil', "Data Majelis Ta'lim '$nama' berhasil dipulihkan.");

            return redirect()->route('skt_piagam_mt.trash');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Gagal memulihkan data. '.$e->getMessage());

            return redirect()->route('skt_piagam_mt.trash');
        }
    }

    /**
     * Menghapus data majelis taklim secara permanen
     */
    public function forceDelete($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::withTrashed()->findOrFail($id);
            $nama = $sktpiagammt->nama_majelis;

            // Hapus file SKT jika ada
            if ($sktpiagammt->file_skt && file_exists(storage_path('app/public/skt/'.$sktpiagammt->file_skt))) {
                unlink(storage_path('app/public/skt/'.$sktpiagammt->file_skt));
            }

            // Hapus file Piagam jika ada
            if ($sktpiagammt->file_piagam && file_exists(storage_path('app/public/piagam/'.$sktpiagammt->file_piagam))) {
                unlink(storage_path('app/public/piagam/'.$sktpiagammt->file_piagam));
            }

            // Hapus file Berkas jika ada
            if ($sktpiagammt->file_berkas && file_exists(storage_path('app/public/berkas/'.$sktpiagammt->file_berkas))) {
                unlink(storage_path('app/public/berkas/'.$sktpiagammt->file_berkas));
            }

            $sktpiagammt->forceDelete();

            // Menggunakan Alert facade untuk menampilkan notifikasi sukses
            Alert::success('Berhasil', "Data Majelis Ta'lim '$nama' berhasil dihapus permanen.");

            return redirect()->route('skt_piagam_mt.trash');
        } catch (\Exception $e) {
            // Menggunakan Alert facade untuk menampilkan notifikasi error
            Alert::error('Gagal', 'Gagal menghapus data permanen. '.$e->getMessage());

            return redirect()->route('skt_piagam_mt.trash');
        }
    }

    // Preview piagam sebagai HTML (untuk debugging)
    public function previewPiagam($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::with(['kecamatan', 'kelurahan'])->findOrFail($id);
            $logoPath = public_path('images/kemenag/kemenag.png');
            $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

            return view('backend.skt_piagam_mt.cetak_piagam', compact('sktpiagammt', 'logoBase64'));
        } catch (\Exception $e) {
            return response('Record tidak ditemukan', 404);
        }
    }
}
