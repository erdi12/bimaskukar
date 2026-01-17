<?php

namespace App\Http\Controllers;

use App\Exports\MarbotExport;
use App\Exports\MarbotTemplateExport;
use App\Exports\MarbotUmrohExport;
use App\Imports\MarbotImport;
use App\Models\Marbot;
use App\Models\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class MarbotController extends Controller
{
    private const UPLOAD_PATH = [
        'marbot' => 'app/public/marbot_files',
    ];

    public function create()
    {
        $kecamatans = \App\Models\Kecamatan::orderBy('kecamatan')->get();

        return view('backend.marbot.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:marbots,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'npwp' => 'required|string|max:50',
            'tanggal_mulai_bekerja' => 'required|date',
            'no_hp' => 'required|numeric',
            'alamat' => 'required|string',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'tipe_rumah_ibadah' => 'nullable',
            'rumah_ibadah_id' => 'nullable',
            // Files are required for new entry
            'file_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_sk_marbot' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_npwp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_pernyataan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            // Optional files
            'nomor_rekening' => 'nullable|string',
            'file_buku_rekening' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan', 'file_buku_rekening', '_token']);

        // Handle File Uploads
        $files = ['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan', 'file_buku_rekening'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $this->handleFileUpload($request->file($file), 'marbot');
            }
        }

        // Set Default Status to Approved
        $data['status'] = 'disetujui';

        // --- Generate NIM Logic ---
        $kecamatan = \App\Models\Kecamatan::find($request->kecamatan_id);
        if ($kecamatan) {
            $kecName = strtolower(trim($kecamatan->kecamatan));
            $kecName = str_replace('kecamatan ', '', $kecName);

            $prefix = config('nomor_induk_marbot.'.$kecName);

            if (! $prefix) {
                // Fallback
                $prefix = '431.2.64.02.'.str_pad($request->kecamatan_id, 2, '0', STR_PAD_LEFT);
            }

            // Find last number
            $last = Marbot::where('nomor_induk_marbot', 'like', $prefix.'.%')
                ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_induk_marbot, ".", -1) AS UNSIGNED) DESC')
                ->first();

            $nextNo = 1;
            if ($last && $last->nomor_induk_marbot) {
                $parts = explode('.', $last->nomor_induk_marbot);
                $nextNo = (int) end($parts) + 1;
            }

            $data['nomor_induk_marbot'] = $prefix.'.'.str_pad($nextNo, 3, '0', STR_PAD_LEFT);
        }
        // -------------------------

        $marbot = Marbot::create($data);

        Alert::success('Berhasil', 'Data Marbot berhasil ditambahkan dan disetujui. NIM: '.$marbot->nomor_induk_marbot);

        return redirect()->route('marbot.index');
    }

    public function index(Request $request)
    {
        $query = Marbot::with(['kecamatan', 'kelurahan', 'insentifs'])->latest();

        // Filters
        if ($request->has('kecamatan_id') && $request->kecamatan_id != '') {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }
        if ($request->has('kelurahan_id') && $request->kelurahan_id != '') {
            $query->where('kelurahan_id', $request->kelurahan_id);
        }

        $marbots = $query->get();
        $kecamatans = \App\Models\Kecamatan::orderBy('kecamatan')->get();
        
        // Fetch Settings
        $startDate = Setting::where('key', 'marbot_register_start')->value('value');
        $endDate = Setting::where('key', 'marbot_register_end')->value('value');

        return view('backend.marbot.index', compact('marbots', 'startDate', 'endDate', 'kecamatans'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Setting::updateOrCreate(
            ['key' => 'marbot_register_start'],
            ['value' => $request->start_date]
        );

        Setting::updateOrCreate(
            ['key' => 'marbot_register_end'],
            ['value' => $request->end_date]
        );

        Alert::success('Berhasil', 'Jadwal registrasi berhasil diperbarui.');

        return redirect()->back();
    }

    public function downloadArchive(Request $request)
    {
        // 1. Set Unlimited Time & Memory untuk proses berat
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'nullable|string',
        ]);

        $startDate = $request->start_date.' 00:00:00';
        $endDate = $request->end_date.' 23:59:59';

        // 2. Query dengan Filter Status (opsional)
        $query = Marbot::with('kecamatan')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $marbots = $query->get();

        if ($marbots->isEmpty()) {
            Alert::warning('Perhatian', 'Tidak ada data marbot pada kriteria tersebut.');

            return redirect()->back();
        }

        // Setup ZIP
        $statusLabel = $request->status ? '_'.$request->status : '_Semua';
        $zipFileName = 'Arsip_Marbot'.$statusLabel.'_'.$request->start_date.'_sd_'.$request->end_date.'.zip';
        $zipFilePath = storage_path('app/public/'.$zipFileName);

        $zip = new \ZipArchive;
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {

            $filesNotFound = []; // Array untuk menampung log

            foreach ($marbots as $marbot) {
                // Nama Folder Kecamatan
                $kecamatanName = $marbot->kecamatan ? $marbot->kecamatan->kecamatan : 'Tanpa_Kecamatan';
                $kecamatanFolder = preg_replace('/[^A-Za-z0-9 _-]/', '', $kecamatanName); // sanitize

                // Nama Folder User: NIK - Nama
                $userFolder = $marbot->nik.' - '.$marbot->nama_lengkap;
                $userFolder = preg_replace('/[^A-Za-z0-9 _-]/', '', $userFolder); // sanitize

                // Path lengkap di dalam ZIP
                $internalPath = $kecamatanFolder.'/'.$userFolder.'/';

                // Pastikan folder kosong pun dibuat
                $zip->addEmptyDir($internalPath);

                // Daftar file yang akan dimasukkan
                $files = [
                    'KTP' => $marbot->file_ktp,
                    'KK' => $marbot->file_kk,
                    'SK_Marbot' => $marbot->file_sk_marbot,
                    'NPWP' => $marbot->file_npwp,
                    'Permohonan' => $marbot->file_permohonan,
                    'Pernyataan' => $marbot->file_pernyataan,
                    'Buku_Rekening' => $marbot->file_buku_rekening,
                ];

                foreach ($files as $label => $filename) {
                    if ($filename) {
                        $realPath = storage_path('app/public/marbot_files/'.$filename);

                        if (file_exists($realPath)) {
                            $extension = pathinfo($filename, PATHINFO_EXTENSION);
                            $zipName = $label.'.'.$extension;
                            $zip->addFile($realPath, $internalPath.$zipName);
                        } else {
                            // 3. Log File Hilang
                            $filesNotFound[] = "[$userFolder] File $label ($filename) terdaftar di database tapi tidak ditemukan fisiknya.";
                        }
                    } else {
                        // Opsional: Log jika user memang belum upload
                        // $filesNotFound[] = "[$userFolder] File $label belum diupload user.";
                    }
                }
            }

            // Jika ada file hilang, buat laporan txt di root ZIP
            if (! empty($filesNotFound)) {
                $logContent = "Laporan File Tidak Ditemukan:\n\n".implode("\n", $filesNotFound);
                $zip->addFromString('MISSING_FILES_LOG.txt', $logContent);
            }

            $zip->close();

            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            Alert::error('Gagal', 'Gagal membuat file arsip.');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $marbot = Marbot::with(['kecamatan', 'kelurahan', 'masjid', 'mushalla'])->where('uuid', $id)->firstOrFail();
        $kecamatans = \App\Models\Kecamatan::orderBy('kecamatan')->get();

        return view('backend.marbot.edit', compact('marbot', 'kecamatans'));
    }

    public function show($id)
    {
        $marbot = Marbot::with(['kecamatan', 'kelurahan', 'masjid', 'mushalla'])->where('uuid', $id)->firstOrFail();

        return view('backend.marbot.show', compact('marbot'));
    }

    public function update(Request $request, $id)
    {
        $marbot = Marbot::with('kecamatan')->where('uuid', $id)->firstOrFail();

        if ($request->action == 'approve') {
            if ($marbot->status == 'disetujui' && $marbot->nomor_induk_marbot) {
                Alert::info('Info', 'Data sudah disetujui sebelumnya.');

                return redirect()->route('marbot.index');
            }

            // Generate NIM
            $kecName = strtolower(trim($marbot->kecamatan->kecamatan));
            $kecName = str_replace('kecamatan ', '', $kecName);

            $prefix = config('nomor_induk_marbot.'.$kecName);

            if (! $prefix) {
                // Fallback
                $prefix = '431.2.64.02.'.str_pad($marbot->kecamatan_id, 2, '0', STR_PAD_LEFT);
            }

            // Find last number
            $last = Marbot::where('nomor_induk_marbot', 'like', $prefix.'.%')
                ->orderByRaw('CAST(SUBSTRING_INDEX(nomor_induk_marbot, ".", -1) AS UNSIGNED) DESC')
                ->first();

            $nextNo = 1;
            if ($last && $last->nomor_induk_marbot) {
                $parts = explode('.', $last->nomor_induk_marbot);
                $nextNo = (int) end($parts) + 1;
            }

            $marbot->nomor_induk_marbot = $prefix.'.'.str_pad($nextNo, 3, '0', STR_PAD_LEFT);
            $marbot->status = 'disetujui';
            $marbot->catatan = null;
            $marbot->verification_details = null;
            $marbot->save();

            Alert::success('Berhasil', 'Permohonan disetujui. NIM diterbitkan: '.$marbot->nomor_induk_marbot);

            return redirect()->route('marbot.index');

        } elseif ($request->action == 'return') {
            $request->validate(['catatan' => 'required']);
            $marbot->status = 'perbaikan';
            $marbot->catatan = $request->catatan;

            if ($request->verification_details) {
                $marbot->verification_details = json_decode($request->verification_details, true);
            }

            $marbot->save();

            Alert::success('Berhasil', 'Permohonan dikembalikan untuk perbaikan.');

            return redirect()->route('marbot.index');

        } elseif ($request->action == 'edit_data') {
            // Admin Edit Data
            $request->validate([
                'nik' => 'required|unique:marbots,nik,'.$marbot->id,
                'nama_lengkap' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'npwp' => 'required|string|max:50',
                'tanggal_mulai_bekerja' => 'required|date',
                'no_hp' => 'required|numeric',
                'alamat' => 'required|string',
                'kecamatan_id' => 'required',
                'kelurahan_id' => 'required',
                'tipe_rumah_ibadah' => 'nullable',
                'rumah_ibadah_id' => 'nullable',
                'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_sk_marbot' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_npwp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'file_pernyataan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'nomor_rekening' => 'nullable|string',
                'file_buku_rekening' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $data = $request->except(['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan', 'file_buku_rekening', '_token', '_method', 'action']);

            // Handle File Uploads
            $files = ['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan', 'file_buku_rekening'];
            foreach ($files as $file) {
                if ($request->hasFile($file)) {
                    $data[$file] = $this->handleFileUpload($request->file($file), 'marbot', $marbot->$file);
                }
            }

            $marbot->update($data);

            Alert::success('Berhasil', 'Data marbot berhasil diperbarui.');

            return redirect()->route('marbot.show', $marbot->uuid);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $marbot = Marbot::where('uuid', $id)->firstOrFail();
        // Delete files
        // Delete files
        $files = ['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan', 'file_buku_rekening'];
        foreach ($files as $f) {
            if ($marbot->$f && \Storage::exists('public/marbot_files/'.$marbot->$f)) {
                \Storage::delete('public/marbot_files/'.$marbot->$f);
            }
        }
        $marbot->delete();

        Alert::success('Berhasil', 'Data marbot berhasil dihapus.');

        return redirect()->route('marbot.index');
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $status = $request->status;

        $filename = 'Data_Marbot_'.($startDate ?? 'Semua').'_sd_'.($endDate ?? 'Semua').'.xlsx';

        return Excel::download(new MarbotExport($startDate, $endDate, $status), $filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new MarbotImport, $request->file('file_excel'));
            Alert::success('Berhasil', 'Data berhasil diimport.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $message = 'Gagal Import. Baris ke-'.$failures[0]->row().': '.implode(', ', $failures[0]->errors());
            Alert::error('Gagal', $message);
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: '.$e->getMessage());
        }

        return redirect()->back();
    }

    public function downloadTemplate()
    {
        return Excel::download(new MarbotTemplateExport, 'Template_Import_Marbot.xlsx');
    }

    public function seleksi(Request $request)
    {
        $minUsia = $request->input('min_usia');
        $minLamaKerja = $request->input('min_lama_kerja');
        $statusUmroh = $request->input('status_umroh', 'all');

        $query = Marbot::with(['kecamatan', 'kelurahan'])
            ->where('status', 'disetujui')
            ->whereNotNull('nomor_induk_marbot');

        // Filter Umroh Status
        if ($statusUmroh !== 'all') {
            if ($statusUmroh == 'belum_terpilih') {
                $query->whereNull('status_umroh');
            } else {
                $query->where('status_umroh', $statusUmroh);
            }
        }

        // Get all potential candidates to filter by age and service duration in memory (easier for dates)
        // or using raw SQL. Let's use collection filtering for simplicity if data isn't huge.
        // But for reliability, let's try to use SQL where possible.

        // Filter by Service Duration (Lama Kerja)
        if ($minLamaKerja > 0) {
            $dateLimit = \Carbon\Carbon::now()->subYears($minLamaKerja);
            $query->whereDate('tanggal_mulai_bekerja', '<=', $dateLimit);
        }

        // Filter by Age (Usia)
        if ($minUsia > 0) {
            $dateLimit = \Carbon\Carbon::now()->subYears($minUsia);
            $query->whereDate('tanggal_lahir', '<=', $dateLimit);
        }

        $candidates = $query->orderBy('status_umroh', 'desc') // Prioritize those already marked
            ->orderBy('tanggal_mulai_bekerja', 'asc') // Longer service first
            ->get();

        // Calculate Age and Service Duration for display
        $candidates->map(function ($marbot) {
            $marbot->usia = \Carbon\Carbon::parse($marbot->tanggal_lahir)->age;
            $marbot->lama_kerja = \Carbon\Carbon::parse($marbot->tanggal_mulai_bekerja)->diffInYears(\Carbon\Carbon::now());

            return $marbot;
        });

        return view('backend.marbot.seleksi', compact('candidates', 'minUsia', 'minLamaKerja', 'statusUmroh'));
    }

    public function prosesSeleksi(Request $request)
    {
        $request->validate([
            'marbot_uuids' => 'required|array',
            'action' => 'required|in:kandidat,terverifikasi,berangkat,batal',
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|integer|min:2024|max:2030',
        ]);

        $uuids = $request->marbot_uuids;
        $action = $request->action;

        // Defaults
        $tahun = $request->filled('tahun') ? $request->tahun : date('Y');
        $bulan = $request->filled('bulan') ? $request->bulan : null;

        if ($action == 'batal') {
            Marbot::whereIn('uuid', $uuids)->update([
                'status_umroh' => null,
                'tahun_umroh' => null,
                'bulan_umroh' => null,
            ]);
            Alert::success('Berhasil', 'Status seleksi berhasil dibatalkan/direset.');
        } else {
            $updateData = ['status_umroh' => $action];
            if ($action == 'berangkat') {
                $updateData['tahun_umroh'] = $tahun;
                $updateData['bulan_umroh'] = $bulan;
            } else {
                // Reset date if moving back to candidate/verified
                $updateData['tahun_umroh'] = null;
                $updateData['bulan_umroh'] = null;
            }

            Marbot::whereIn('uuid', $uuids)->update($updateData);

            $messages = [
                'kandidat' => 'Data marbot ditandai sebagai Kandidat.',
                'terverifikasi' => 'Data marbot berhasil diverifikasi untuk umroh.',
            ];

            if ($action == 'berangkat') {
                $monthName = $bulan ? \Carbon\Carbon::create(null, (int) $bulan, 1)->locale('id')->monthName : '';
                $msg = 'Data marbot ditandai Berangkat Umroh';
                if ($monthName) {
                    $msg .= ' bulan '.$monthName;
                }
                $msg .= ' tahun '.$tahun.'.';
                Alert::success('Berhasil', $msg);
            } else {
                Alert::success('Berhasil', $messages[$action]);
            }
        }

        return redirect()->route('marbot.seleksi');
    }

    public function exportUmroh(Request $request)
    {
        $tahun = $request->input('tahun'); // Optional filter by year
        $filename = 'Data_Jamaah_Umroh_'.($tahun ? $tahun : 'Semua').'.xlsx';

        return Excel::download(new MarbotUmrohExport($tahun), $filename);
    }

    public function processInsentif(Request $request)
    {
        $request->validate([
            'marbot_uuids' => 'required|array',
            'tahun_anggaran' => 'required|integer|min:2024|max:2030',
            'bulan' => 'required|integer|min:1|max:12',
            'nominal' => 'required|numeric|min:0',
            'tanggal_terima' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $marbotIds = Marbot::whereIn('uuid', $request->marbot_uuids)->pluck('id');
        $count = 0;

        $namaBulan = \Carbon\Carbon::createFromDate(null, $request->bulan, 1)->translatedFormat('F');

        foreach ($marbotIds as $marbotId) {
            // Check if already exists for this year AND month
            $exists = \App\Models\MarbotInsentif::where('marbot_id', $marbotId)
                ->where('tahun_anggaran', $request->tahun_anggaran)
                ->where('bulan', $request->bulan)
                ->exists();

            if (! $exists) {
                \App\Models\MarbotInsentif::create([
                    'marbot_id' => $marbotId,
                    'tahun_anggaran' => $request->tahun_anggaran,
                    'bulan' => $request->bulan,
                    'nominal' => $request->nominal,
                    'tanggal_terima' => $request->tanggal_terima,
                    'keterangan' => $request->keterangan,
                ]);
                $count++;
            }
        }

        if ($count > 0) {
            Alert::success('Berhasil', $count.' marbot berhasil ditandai sudah menerima insentif bulan '.$namaBulan.' '.$request->tahun_anggaran);
        } else {
            Alert::info('Info', 'Tidak ada data baru yang disimpan (mungkin sudah terdaftar untuk periode ini).');
        }

        return redirect()->back();
    }

    /**
     * Handle file upload for Marbot
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
        $extension = $file->getClientOriginalExtension();
        $safeName = preg_replace('/[^A-Za-z0-9\-]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = time().'_'.$safeName.'.'.$extension;

        // Pastikan direktori ada
        $path = storage_path(self::UPLOAD_PATH[$type]);
        if (! file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Upload file
        $file->move($path, $fileName);

        return $fileName;
    }
}
