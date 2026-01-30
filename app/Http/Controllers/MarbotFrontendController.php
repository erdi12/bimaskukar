<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Marbot;
use App\Models\Setting;
use App\Models\SktMasjid;
use App\Models\SktMushalla;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarbotFrontendController extends Controller
{
    private const UPLOAD_PATH = [
        'marbot_files' => 'app/public/marbot_files',
    ];

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

        // Sanitasi dan generate nama file baru (Removed Str::slug usage on name to match example logic which used preg_replace, or kept it if user desires, example used preg_replace)
        // User example: $fileName = time().'_'.preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
        // Current logic: time().'_'.Str::slug($request->nama_lengkap).'_'.$file.'.'.$uploadedFile->getClientOriginalExtension();
        // I will adapt the current logic (which is better naming with user name) but use the move/storage_path mechanism requested.

        // Sanitasi dan generate nama file baru dengan ekstensi yang aman
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

    public function create()
    {
        // Check Registration Schedule
        $startDate = Setting::where('key', 'marbot_register_start')->value('value');
        $endDate = Setting::where('key', 'marbot_register_end')->value('value');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $now = Carbon::now();

            if ($now->lt($start) || $now->gt($end)) {
                return view('frontend.marbot.closed', compact('start', 'end'));
            }
        }

        $kecamatans = Kecamatan::all();

        return view('frontend.marbot.create', compact('kecamatans'));
    }

    public function checkRumahIbadah(Request $request)
    {
        $type = $request->type; // Masjid or Mushalla
        $id = $request->id; // Using nomor_id_masjid or id

        $data = null;
        $foundInOther = false;

        if ($type == 'Masjid') {
            $query = SktMasjid::with(['kecamatan', 'kelurahan'])->where('nomor_id_masjid', $id);
            if (is_numeric($id)) {
                $query->orWhere('id', $id);
            }
            $data = $query->first();

            // Check in Mushalla if not found
            if (! $data) {
                $otherQuery = SktMushalla::where('nomor_id_mushalla', $id);
                if (is_numeric($id)) {
                    $otherQuery->orWhere('id', $id);
                }
                if ($otherQuery->exists()) {
                    $foundInOther = true;
                }
            }
        } else {
            $query = SktMushalla::with(['kecamatan', 'kelurahan'])->where('nomor_id_mushalla', $id);
            if (is_numeric($id)) {
                $query->orWhere('id', $id);
            }
            $data = $query->first();

            // Check in Masjid if not found
            if (! $data) {
                $otherQuery = SktMasjid::where('nomor_id_masjid', $id);
                if (is_numeric($id)) {
                    $otherQuery->orWhere('id', $id);
                }
                if ($otherQuery->exists()) {
                    $foundInOther = true;
                }
            }
        }

        if ($data) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $data->id,
                    'nama' => $type == 'Masjid' ? $data->nama_masjid : $data->nama_mushalla,
                    'alamat' => $type == 'Masjid' ? $data->alamat_masjid : $data->alamat_mushalla,
                    'kecamatan' => $data->kecamatan->kecamatan,
                    'kelurahan' => $data->kelurahan->nama_kelurahan,
                ],
            ]);
        }

        if ($foundInOther) {
            $otherType = $type == 'Masjid' ? 'Mushalla' : 'Masjid';

            return response()->json([
                'status' => 'error',
                'message' => "Data ditemukan di kategori {$otherType}. Silakan ubah Tipe Rumah Ibadah menjadi {$otherType}.",
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan. Pastikan ID/Nomor Statistik benar.']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|unique:marbots,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'npwp' => 'required|string|max:50',
            'tanggal_mulai_bekerja' => 'required|date',
            'no_hp' => 'required|numeric',
            'alamat' => 'required|string',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'tipe_rumah_ibadah' => 'required',
            'rumah_ibadah_id' => 'required', // This should be the real ID hidden in the form
            'file_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_sk_marbot' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_npwp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_pernyataan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan']);

        // Handle File Uploads
        $files = ['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $this->handleFileUpload($request->file($file), 'marbot_files');
            }
        }

        $data['status'] = 'diajukan';

        $marbot = Marbot::create($data);

        // Send Notification to Admins
        try {
            $users = \App\Models\User::all(); // Or filter by role 'Admin'/'Operator'
            foreach($users as $user) {
                $user->notify(new \App\Notifications\NewMarbotNotification($marbot));
            }
        } catch (\Exception $e) {
            // Log error but continue
            \Illuminate\Support\Facades\Log::error("Notification Error: " . $e->getMessage());
        }

        return redirect()->route('marbot.register')->with('success', 'Permohonan Nomor Induk Marbot berhasil diajukan. Silakan tunggu verifikasi.');
    }

    public function edit(Request $request, $id)
    {
        $marbot = Marbot::with(['kecamatan', 'kelurahan', 'masjid', 'mushalla'])->where('uuid', $id)->firstOrFail();

        // Security Check: Verify NIK matches (Anti-IDOR)
        if ($request->query('nik') != $marbot->nik) {
            return redirect()->route('cek_validitas')->with('error', 'Akses ditolak. NIK tidak sesuai.');
        }

        // Ensure only 'perbaikan' status can be edited
        if ($marbot->status != 'perbaikan') {
            return redirect()->route('cek_validitas')->with('error', 'Hanya data dengan status perbaikan yang dapat diedit.');
        }

        // Check Deadline
        if ($marbot->deadline_perbaikan && $marbot->deadline_perbaikan->endOfDay()->isPast()) {
            return redirect()->route('cek_validitas')->with('error', 'Batas waktu perbaikan telah habis. Permohonan Anda otomatis ditolak.');
        }

        $kecamatans = Kecamatan::all();

        return view('frontend.marbot.edit', compact('marbot', 'kecamatans'));
    }

    public function update(Request $request, $id)
    {
        $marbot = Marbot::where('uuid', $id)->firstOrFail();

        if ($marbot->status != 'perbaikan') {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'nik' => 'required|unique:marbots,nik,'.$marbot->id,
            'nama_lengkap' => 'required',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'npwp' => 'required|string|max:50',
            'tanggal_mulai_bekerja' => 'required|date',
            'alamat' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'tipe_rumah_ibadah' => 'required',
            'rumah_ibadah_id' => 'required',
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_sk_marbot' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_npwp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_pernyataan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except(['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan', '_token', '_method']);

        // Handle File Uploads
        $files = ['file_ktp', 'file_kk', 'file_sk_marbot', 'file_npwp', 'file_permohonan', 'file_pernyataan'];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $data[$file] = $this->handleFileUpload($request->file($file), 'marbot_files', $marbot->$file);
            }
        }

        // Reset status to diajukan after update
        $data['status'] = 'diajukan';
        // Keep old notes for admin reference
        // $data['catatan'] = null;

        $marbot->update($data);

        return redirect()->route('cek_validitas.show', [
            'type' => 'marbot', 
            'uuid' => $marbot->uuid
        ])->with('success', 'Data permohonan berhasil diperbaiki dan diajukan kembali untuk diverifikasi.');
    }
}
