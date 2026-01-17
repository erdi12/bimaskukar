<?php

namespace App\Http\Controllers;

use App\Exports\SktpiagammtExport;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Sktpiagammt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class SktpiagammtV2Controller extends Controller
{
    private const UPLOAD_PATH = [
        // 'skt' => 'app/public/skt',
        // 'piagam' => 'app/public/piagam',
        // 'berkas' => 'app/public/berkas',
        'skt' => 'skt',
        'piagam' => 'piagam',
        'berkas' => 'berkas',
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
        $folder = self::UPLOAD_PATH[$type];

        // Hapus file lama jika ada
        // if ($oldFile && file_exists(storage_path(self::UPLOAD_PATH[$type].'/'.$oldFile))) {
        //     unlink(storage_path(self::UPLOAD_PATH[$type].'/'.$oldFile));
        // }
        if ($oldFile && Storage::disk('public')->exists($folder.'/'.$oldFile)) {
            Storage::disk('public')->delete($folder.'/'.$oldFile);
        }

        // Sanitasi dan generate nama file baru
        $extension = $file->getClientOriginalExtension();
        $safeName = preg_replace('/[^A-Za-z0-9\-]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = time().'_'.$safeName.'.'.$extension;

        // Pastikan direktori ada
        // $path = storage_path(self::UPLOAD_PATH[$type]);
        // if (! file_exists($path)) {
        //     mkdir($path, 0755, true);
        // }

        // Upload file
        // $file->move($path, $fileName);
        Storage::disk('public')->putFileAs($folder, $file, $fileName);

        return $fileName;
    }

    /**
     * Display a listing of the resource (V2 version).
     */
    public function index()
    {
        // Ambil data yang diperlukan untuk filter
        $kecamatans = \App\Models\Kecamatan::all();
        $kelurahans = collect();

        // Load kelurahan jika ada filter kecamatan
        if (request()->has('kecamatan_id') && request('kecamatan_id')) {
            $kelurahans = \App\Models\Kelurahan::where('kecamatan_id', request('kecamatan_id'))->get();
        }

        // Jika request AJAX (dari DataTables)
        if (request()->ajax()) {
            $query = \App\Models\Sktpiagammt::with(['kecamatan', 'kelurahan']);

            // Apply filters
            if (request()->has('kecamatan_id') && request('kecamatan_id')) {
                $query->where('kecamatan_id', request('kecamatan_id'));
            }
            if (request()->has('kelurahan_id') && request('kelurahan_id')) {
                $query->where('kelurahan_id', request('kelurahan_id'));
            }

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('nama_majelis', function ($row) {
                    return '<span class="fw-bold">'.$row->nama_majelis.'</span>';
                })
                ->addColumn('alamat_lengkap', function ($row) {
                    return $row->alamat.', '.
                           ($row->kelurahan->jenis_kelurahan == 'Kelurahan' ? 'Kel.' : 'Desa').' '.
                           $row->kelurahan->nama_kelurahan.', Kec. '.
                           ucwords($row->kecamatan->kecamatan);
                })
                ->addColumn('status_badge', function ($row) {
                    $mendaftarUlang = $row->mendaftar_ulang ? \Carbon\Carbon::parse($row->mendaftar_ulang) : null;
                    $now = \Carbon\Carbon::today();

                    // Prioritas 1: Sudah Lewat Tanggal (Belum Update)
                    if ($mendaftarUlang && $now->gt($mendaftarUlang)) {
                        return '<span class="badge bg-warning text-black rounded-pill">Belum Update</span>';
                    }

                    // Prioritas 2: Akan Habis dalam 30 Hari (Segera Habis)
                    if ($mendaftarUlang && $now->diffInDays($mendaftarUlang, false) <= 30 && $now->diffInDays($mendaftarUlang, false) >= 0) {
                        return '<span class="badge bg-warning text-black rounded-pill" title="Habis dalam '.$now->diffInDays($mendaftarUlang).' hari">Segera Habis</span>';
                    }

                    // Prioritas 3: Status Aktif/Nonaktif Normal
                    if ($row->status == 'aktif') {
                        return '<span class="badge bg-success rounded-pill">Aktif</span>';
                    } elseif ($row->status == 'nonaktif') {
                        return '<span class="badge bg-secondary rounded-pill">Non-Aktif</span>';
                    } else {
                        return '<span class="badge bg-warning text-black rounded-pill">Belum Update</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="btn-group btn-group-sm mb-2" role="group">';
                    $actionBtn .= '<a href="'.route('skt_piagam_mt.cetak_skt', $row->id).'" class="btn btn-success d-inline-flex align-items-center" target="_blank">';
                    $actionBtn .= '<i class="fas fa-file-lines me-1"></i> Cetak SKT</a>';

                    $actionBtn .= '<a href="'.route('skt_piagam_mt.cetak_piagam', $row->id).'" class="btn btn-warning d-inline-flex align-items-center" target="_blank">';
                    $actionBtn .= '<i class="fas fa-certificate me-1"></i> Cetak Piagam</a>';
                    $actionBtn .= '</div>';

                    // Tombol WhatsApp Reminder (jika status perlu diingatkan)
                    $mendaftarUlang = $row->mendaftar_ulang ? \Carbon\Carbon::parse($row->mendaftar_ulang) : null;
                    $now = \Carbon\Carbon::today();
                    $isExpired = $mendaftarUlang && $now->gt($mendaftarUlang);
                    $isExpiringSoon = $mendaftarUlang && $now->diffInDays($mendaftarUlang, false) <= 30 && $now->diffInDays($mendaftarUlang, false) >= 0;

                    if ($row->no_hp && ($isExpired || $isExpiringSoon)) {
                        // Format HP: 08xx -> 628xx
                        $hp = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $row->no_hp));
                        $nama = $row->nama_majelis; // Jangan di-urlencode dulu agar tidak double encode/muncul +
                        $tgl = $row->mendaftar_ulang ? \Carbon\Carbon::parse($row->mendaftar_ulang)->format('d-m-Y') : '-';
                        // Gunakan rawurlencode agar spasi menjadi %20 bukan +
                        $pesan = rawurlencode("Assalamu'alaikum, Admin SIBERKAT menginformasikan bahwa masa berlaku SKT Majelis Taklim *$nama* akan/telah habis pada tanggal *$tgl*. Mohon segera lakukan perpanjangan/daftar ulang. Terimakasih.");

                        $actionBtn .= '<div class="mb-2">';
                        $actionBtn .= '<a href="https://wa.me/'.$hp.'?text='.$pesan.'" class="btn btn-success btn-sm w-100 d-inline-flex align-items-center justify-content-center" target="_blank">';
                        $actionBtn .= '<i class="fab fa-whatsapp me-1"></i> Ingatkan WA</a>';
                        $actionBtn .= '</div>';
                    }

                    $actionBtn .= '<div class="btn-group btn-group-sm" role="group">';

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator')) {
                        $actionBtn .= '<a href="'.route('skt_piagam_mt_v2.show', $row->uuid).'" class="btn btn-info d-inline-flex align-items-center">';
                        $actionBtn .= '<i class="fas fa-eye me-1"></i> Lihat</a>';

                        $actionBtn .= '<a href="'.route('skt_piagam_mt_v2.edit', $row->uuid).'" class="btn btn-primary d-inline-flex align-items-center">';
                        $actionBtn .= '<i class="fas fa-edit me-1"></i> Edit</a>';
                    }

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $actionBtn .= '<button type="button" class="btn btn-danger d-inline-flex align-items-center" onclick="confirmDelete(\''.$row->uuid.'\')">';
                        $actionBtn .= '<i class="fas fa-trash me-1"></i> Hapus</button>';

                        // Form Delete Hidden
                        $actionBtn .= '<form id="delete-form-'.$row->uuid.'" action="'.route('skt_piagam_mt_v2.destroy', $row->uuid).'" method="POST" style="display: none;">';
                        $actionBtn .= csrf_field();
                        $actionBtn .= method_field('DELETE');
                        $actionBtn .= '</form>';
                    }

                    if (auth()->user()->hasRole('Viewer')) {
                        $actionBtn .= '<a href="'.route('skt_piagam_mt_v2.show', $row->uuid).'" class="btn btn-info d-inline-flex align-items-center">';
                        $actionBtn .= '<i class="fas fa-eye me-1"></i> Lihat</a>';
                    }

                    $actionBtn .= '</div>';

                    return $actionBtn;
                })
                ->filterColumn('nama_majelis', function ($query, $keyword) {
                    $query->where('nama_majelis', 'like', "%{$keyword}%");
                })
                ->filterColumn('alamat_lengkap', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('alamat', 'like', "%{$keyword}%")
                            ->orWhereHas('kelurahan', function ($q2) use ($keyword) {
                                $q2->where('nama_kelurahan', 'like', "%{$keyword}%");
                            })
                            ->orWhereHas('kecamatan', function ($q2) use ($keyword) {
                                $q2->where('kecamatan', 'like', "%{$keyword}%");
                            });
                    });
                })
                ->rawColumns(['nama_majelis', 'status_badge', 'action'])
                ->make(true);
        }

        // Jika bukan AJAX, return view biasa
        return view('backend.skt_piagam_mt_v2.index', compact('kecamatans', 'kelurahans'));
    }

    /**
     * Show the form for creating a new resource (V2 version).
     */
    public function create()
    {
        $kecamatans = \App\Models\Kecamatan::all();

        return view('backend.skt_piagam_mt_v2.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage (V2 version).
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
            'jumlah_anggota' => 'nullable|integer',
            'materi' => 'nullable',
        ]);

        // Handle materi input: Trim whitespace, Unique, Title Case
        if ($request->has('materi') && is_array($request->materi)) {
            $materiArray = array_map(function ($item) {
                return ucwords(strtolower(trim($item))); // Bersihkan spasi, format Title Case
            }, $request->materi);

            $materiArray = array_unique($materiArray); // Hapus duplikat
            $validated['materi'] = implode(', ', $materiArray);
        }

        Sktpiagammt::create($validated);

        Alert::success('Success', 'Data Majelis Ta\'lim berhasil ditambahkan.');

        return redirect()->route('skt_piagam_mt_v2.index');
    }

    /**
     * Display the specified resource (V2 version).
     */
    public function show(string $id)
    {
        $sktpiagammt = \App\Models\Sktpiagammt::with(['kecamatan', 'kelurahan'])->where('uuid', $id)->firstOrFail();

        return view('backend.skt_piagam_mt_v2.show', compact('sktpiagammt'));
    }

    /**
     * Show the form for editing the specified resource (V2 version).
     */
    public function edit(string $id)
    {
        $sktpiagammt = \App\Models\Sktpiagammt::where('uuid', $id)->firstOrFail();
        $kecamatans = \App\Models\Kecamatan::all();

        return view('backend.skt_piagam_mt_v2.edit', compact('sktpiagammt', 'kecamatans'));
    }

    /**
     * Update the specified resource in storage (V2 version).
     */
    public function update(Request $request, string $id)
    {
        $sktpiagammt = Sktpiagammt::where('uuid', $id)->firstOrFail();

        $validated = $request->validate([
            'nomor_statistik' => 'required|unique:sktpiagammts,nomor_statistik,'.$sktpiagammt->id, // Use ID for unique ignore
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
            'jumlah_anggota' => 'nullable|integer',
            'materi' => 'nullable',
        ]);

        // Handle materi input: Trim whitespace, Unique, Title Case
        if ($request->has('materi') && is_array($request->materi)) {
            $materiArray = array_map(function ($item) {
                return ucwords(strtolower(trim($item))); // Bersihkan spasi, format Title Case
            }, $request->materi);

            $materiArray = array_unique($materiArray); // Hapus duplikat
            $validated['materi'] = implode(', ', $materiArray);
        }

        $sktpiagammt->update($validated);

        Alert::success('Success', 'Data Majelis Ta\'lim berhasil diperbarui.');

        return redirect()->route('skt_piagam_mt_v2.index');
    }

    /**
     * Display trash page (V2 version).
     */
    public function trash()
    {
        $sktpiagammts = \App\Models\Sktpiagammt::onlyTrashed()
            ->with(['kecamatan', 'kelurahan'])
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('backend.skt_piagam_mt_v2.trash', compact('sktpiagammts'));
    }

    /**
     * Display rekap page (V2 version).
     */
    public function rekap()
    {
        $kecamatans = \App\Models\Kecamatan::all();
        $sktpiagammts = \App\Models\Sktpiagammt::with(['kecamatan', 'kelurahan'])->get();

        // Hitung total keseluruhan
        $totalAktif = $sktpiagammts->where('status', 'aktif')->count();
        $totalNonaktif = $sktpiagammts->where('status', 'nonaktif')->count();
        $totalBelumUpdate = $sktpiagammts->where('status', 'belum_update')->count();

        // Hitung data per kecamatan
        $kecamatanData = [];
        foreach ($kecamatans as $kecamatan) {
            $mtInKecamatan = $sktpiagammts->where('kecamatan_id', $kecamatan->id);
            // Gunakan nama kecamatan sebagai key untuk memudahkan akses di JS chart jika diperlukan,
            // atau simpan struktur data lengkap
            $kecamatanData[$kecamatan->kecamatan] = [
                'nama' => $kecamatan->kecamatan,
                'total' => $mtInKecamatan->count(),
                'aktif' => $mtInKecamatan->where('status', 'aktif')->count(),
                'nonaktif' => $mtInKecamatan->where('status', 'nonaktif')->count(),
                'belum_update' => $mtInKecamatan->where('status', 'belum_update')->count(),
            ];
        }

        return view('backend.skt_piagam_mt_v2.rekap', compact(
            'kecamatanData',
            'sktpiagammts',
            'totalAktif',
            'totalNonaktif',
            'totalBelumUpdate'
        ));
    }

    /**
     * Upload file SKT
     */
    public function uploadSkt(Request $request)
    {
        $request->validate([
            'skt_id' => 'required|exists:sktpiagammts,uuid',
            'skt_file' => 'required|file|mimes:pdf|max:5120', // max 5MB, hanya PDF
        ]);

        try {
            $sktpiagammt = Sktpiagammt::where('uuid', $request->skt_id)->firstOrFail();

            // Upload file menggunakan helper method
            $fileName = $this->handleFileUpload(
                $request->file('skt_file'),
                'skt',
                $sktpiagammt->file_skt
            );

            // Update database
            $sktpiagammt->file_skt = $fileName;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.show', $sktpiagammt->uuid)
                ->with('success', 'File SKT berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Upload SKT Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.show', $request->skt_id)
                ->with('error', 'Terjadi kesalahan saat upload file SKT. Silakan coba lagi.');
        }
    }

    /**
     * Upload file Piagam
     */
    public function uploadPiagam(Request $request)
    {
        $request->validate([
            'piagam_id' => 'required|exists:sktpiagammts,uuid',
            'piagam_file' => 'required|file|mimes:pdf|max:5120', // max 5MB, hanya PDF
        ]);

        try {
            $sktpiagammt = Sktpiagammt::where('uuid', $request->piagam_id)->firstOrFail();

            // Upload file menggunakan helper method
            $fileName = $this->handleFileUpload(
                $request->file('piagam_file'),
                'piagam',
                $sktpiagammt->file_piagam
            );

            // Update database
            $sktpiagammt->file_piagam = $fileName;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.show', $sktpiagammt->uuid)
                ->with('success', 'File Piagam berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Upload Piagam Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.show', $request->piagam_id)
                ->with('error', 'Terjadi kesalahan saat upload piagam. Silakan coba lagi.');
        }
    }

    /**
     * Upload file berkas majelis ta'lim
     */
    public function uploadBerkas(Request $request)
    {
        $request->validate([
            'berkas_id' => 'required|exists:sktpiagammts,uuid',
            'berkas_file' => 'required|file|mimes:pdf|max:5120', // max 5MB, hanya PDF
        ]);

        try {
            $sktpiagammt = Sktpiagammt::where('uuid', $request->berkas_id)->firstOrFail();

            // Upload file menggunakan helper method
            $fileName = $this->handleFileUpload(
                $request->file('berkas_file'),
                'berkas',
                $sktpiagammt->file_berkas
            );

            // Update database
            $sktpiagammt->file_berkas = $fileName;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.show', $sktpiagammt->uuid)
                ->with('success', 'Berkas Majelis Ta\'lim berhasil diupload.');
        } catch (\Exception $e) {
            \Log::error('Upload Berkas Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.show', $request->berkas_id)
                ->with('error', 'Terjadi kesalahan saat upload berkas. Silakan coba lagi.');
        }
    }

    /**
     * Hapus file SKT
     */
    public function deleteSkt($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::where('uuid', $id)->firstOrFail();

            // Hapus file jika ada
            // if ($sktpiagammt->file_skt && file_exists(storage_path(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt))) {
            //     unlink(storage_path(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt));
            // }
            if ($sktpiagammt->file_skt && Storage::disk('public')->exists(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt)) {
                Storage::disk('public')->delete(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt);
            }

            // Update database
            $sktpiagammt->file_skt = null;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.show', $id)
                ->with('success', 'File SKT berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Delete SKT Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.show', $id)
                ->with('error', 'Terjadi kesalahan saat menghapus file SKT. Silakan coba lagi.');
        }
    }

    /**
     * Hapus file Piagam
     */
    public function deletePiagam($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::where('uuid', $id)->firstOrFail();

            // Hapus file jika ada
            // if ($sktpiagammt->file_piagam && file_exists(storage_path(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam))) {
            //     unlink(storage_path(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam));
            // }
            if ($sktpiagammt->file_piagam && Storage::disk('public')->exists(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam)) {
                Storage::disk('public')->delete(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam);
            }

            // Update database
            $sktpiagammt->file_piagam = null;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.show', $id)
                ->with('success', 'File Piagam berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Delete Piagam Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.show', $id)
                ->with('error', 'Terjadi kesalahan saat menghapus file Piagam. Silakan coba lagi.');
        }
    }

    /**
     * Hapus file Berkas
     */
    public function deleteBerkas($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::where('uuid', $id)->firstOrFail();

            // Hapus file jika ada
            // if ($sktpiagammt->file_berkas && file_exists(storage_path(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas))) {
            //     unlink(storage_path(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas));
            // }
            if ($sktpiagammt->file_berkas && Storage::disk('public')->exists(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas)) {
                Storage::disk('public')->delete(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas);
            }

            // Update database
            $sktpiagammt->file_berkas = null;
            $sktpiagammt->save();

            return redirect()->route('skt_piagam_mt_v2.show', $id)
                ->with('success', 'Berkas Majelis Ta\'lim berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Delete Berkas Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.show', $id)
                ->with('error', 'Terjadi kesalahan saat menghapus berkas. Silakan coba lagi.');
        }
    }

    /**
     * Restore data from trash
     */
    public function restore($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::onlyTrashed()->where('uuid', $id)->firstOrFail();
            $sktpiagammt->restore();

            return redirect()->route('skt_piagam_mt_v2.trash')
                ->with('success', 'Data Majelis Ta\'lim berhasil dipulihkan.');
        } catch (\Exception $e) {
            \Log::error('Restore Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.trash')
                ->with('error', 'Gagal memulihkan data. Silakan coba lagi.');
        }
    }

    /**
     * Force delete data (permanen)
     */
    public function forceDelete($id)
    {
        try {
            $sktpiagammt = Sktpiagammt::onlyTrashed()->where('uuid', $id)->firstOrFail();

            // Hapus file fisik jika ada
            // if ($sktpiagammt->file_skt && file_exists(storage_path(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt))) {
            //     unlink(storage_path(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt));
            // }
            // if ($sktpiagammt->file_piagam && file_exists(storage_path(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam))) {
            //     unlink(storage_path(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam));
            // }
            // if ($sktpiagammt->file_berkas && file_exists(storage_path(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas))) {
            //     unlink(storage_path(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas));
            // }
            if ($sktpiagammt->file_skt && Storage::disk('public')->exists(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt)) {
                Storage::disk('public')->delete(self::UPLOAD_PATH['skt'].'/'.$sktpiagammt->file_skt);
            }
            if ($sktpiagammt->file_piagam && Storage::disk('public')->exists(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam)) {
                Storage::disk('public')->delete(self::UPLOAD_PATH['piagam'].'/'.$sktpiagammt->file_piagam);
            }
            if ($sktpiagammt->file_berkas && Storage::disk('public')->exists(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas)) {
                Storage::disk('public')->delete(self::UPLOAD_PATH['berkas'].'/'.$sktpiagammt->file_berkas);
            }

            $sktpiagammt->forceDelete();

            return redirect()->route('skt_piagam_mt_v2.trash')
                ->with('success', 'Data Majelis Ta\'lim berhasil dihapus permanen.');
        } catch (\Exception $e) {
            \Log::error('Force Delete Error: '.$e->getMessage());

            return redirect()->route('skt_piagam_mt_v2.trash')
                ->with('error', 'Gagal menghapus data permanen. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(string $id)
    {
        // dd('Debug: Masuk Method Destroy', $id); // Uncomment untuk debug
        try {
            $sktpiagammt = Sktpiagammt::where('uuid', $id)->firstOrFail();
            $nama = $sktpiagammt->nama_majelis;

            $sktpiagammt->delete();

            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('success', "Data Majelis Ta'lim '$nama' berhasil dihapus (masuk ke sampah).");
        } catch (\Exception $e) {
            return redirect()->route('skt_piagam_mt_v2.index')
                ->with('error', 'Gagal menghapus data. '.$e->getMessage());
        }
    }

    /**
     * Export data to Excel
     */
    public function export()
    {
        return Excel::download(new SktpiagammtExport, 'data_majelis_taklim_'.date('Y-m-d_H-i-s').'.xlsx');
    }
}
