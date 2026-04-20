<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\PengajuanBerkas;
use App\Models\SeleksiBerkas;
use Illuminate\Http\Request;

class PengajuanBerkasController extends Controller
{
    private const UPLOAD_PATH = 'app/public/berkas_seleksi';

    /**
     * Tampilkan form pengajuan berdasarkan slug seleksi.
     */
    public function show($slug)
    {
        $seleksi = SeleksiBerkas::where('slug', $slug)->firstOrFail();

        if (! $seleksi->isBuka()) {
            return view('frontend.seleksi_berkas.tutup', compact('seleksi'));
        }

        $kecamatans = Kecamatan::orderBy('kecamatan')->get();

        return view('frontend.seleksi_berkas.show', compact('seleksi', 'kecamatans'));
    }

    /**
     * Proses submit form pengajuan.
     */
    public function store(Request $request, $slug)
    {
        $seleksi = SeleksiBerkas::where('slug', $slug)->firstOrFail();

        if (! $seleksi->isBuka()) {
            return back()->with('error', 'Seleksi berkas ini sudah tidak dapat diakses.');
        }

        // Validasi field wajib dasar
        $request->validate([
            'nama_pengaju' => 'required|string|max:255',
            'no_hp'        => 'required|string|max:20',
        ]);

        // Validasi field dinamis dari field_configs
        $fieldRules = [];
        $fieldConfigs = $seleksi->field_configs ?? [];
        foreach ($fieldConfigs as $field) {
            $fieldName = 'field_' . $field['name'];
            $type = $field['type'] ?? 'text';

            // Kecamatan & kelurahan pakai validasi exists
            if ($type === 'kecamatan') {
                $fieldRules[$fieldName] = empty($field['required']) ? 'nullable|exists:kecamatans,id' : 'required|exists:kecamatans,id';
                continue;
            }
            if ($type === 'kelurahan') {
                $fieldRules[$fieldName] = empty($field['required']) ? 'nullable|exists:kelurahans,id' : 'required|exists:kelurahans,id';
                continue;
            }
            // Checkbox: array of strings
            if ($type === 'checkbox') {
                $fieldRules[$fieldName]       = empty($field['required']) ? 'nullable|array' : 'required|array';
                $fieldRules[$fieldName . '.*'] = 'string|max:255';
                continue;
            }
            // Signature: base64 string (bisa panjang)
            if ($type === 'signature') {
                $fieldRules[$fieldName] = empty($field['required']) ? 'nullable|string' : 'required|string';
                continue;
            }
            // Select: string satu pilihan
            if ($type === 'select') {
                $fieldRules[$fieldName] = empty($field['required']) ? 'nullable|string|max:255' : 'required|string|max:255';
                continue;
            }

            $rules = [];
            if (! empty($field['required'])) {
                $rules[] = 'required';
            } else {
                $rules[] = 'nullable';
            }
            if ($type === 'number') {
                $rules[] = 'numeric';
            } elseif ($type === 'date') {
                $rules[] = 'date';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:500';
            }
            $fieldRules[$fieldName] = implode('|', $rules);
        }

        // Validasi berkas dari berkas_configs
        $berkasRules = [];
        $berkasConfigs = $seleksi->berkas_configs ?? [];
        foreach ($berkasConfigs as $berkas) {
            $berkasName = 'berkas_' . $berkas['name'];
            $ruleArr = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];
            if (! empty($berkas['required'])) {
                array_unshift($ruleArr, 'required');
            } else {
                array_unshift($ruleArr, 'nullable');
            }
            $berkasRules[$berkasName] = $ruleArr;
        }

        $request->validate(array_merge($fieldRules, $berkasRules));

        // Kumpulkan data isian dinamis
        $dataIsian = [];
        foreach ($fieldConfigs as $field) {
            $fieldName = 'field_' . $field['name'];
            $type      = $field['type'] ?? 'text';

            // Untuk kecamatan & kelurahan, simpan nama bukan ID agar mudah dibaca
            if ($type === 'kecamatan') {
                $kec = Kecamatan::find($request->input($fieldName));
                $dataIsian[$field['name']] = [
                    'label' => $field['label'],
                    'type'  => $type,
                    'value' => $kec ? $kec->kecamatan : $request->input($fieldName),
                ];
            } elseif ($type === 'kelurahan') {
                $kel = Kelurahan::find($request->input($fieldName));
                $dataIsian[$field['name']] = [
                    'label' => $field['label'],
                    'type'  => $type,
                    'value' => $kel ? $kel->nama_kelurahan : $request->input($fieldName),
                ];
            } elseif ($type === 'checkbox') {
                // Checkbox: simpan sebagai array pilihan yang dicentang
                $dataIsian[$field['name']] = [
                    'label' => $field['label'],
                    'type'  => $type,
                    'value' => (array) $request->input($fieldName, []),
                ];
            } elseif ($type === 'signature') {
                // Signature: simpan base64 image string apa adanya
                $dataIsian[$field['name']] = [
                    'label' => $field['label'],
                    'type'  => $type,
                    'value' => $request->input($fieldName, ''),
                ];
            } else {
                $dataIsian[$field['name']] = [
                    'label' => $field['label'],
                    'type'  => $type,
                    'value' => $request->input($fieldName),
                ];
            }
        }


        // Upload berkas
        $berkasFiles = [];
        $uploadPath = storage_path(self::UPLOAD_PATH);
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($berkasConfigs as $berkas) {
            $berkasName = 'berkas_' . $berkas['name'];
            if ($request->hasFile($berkasName)) {
                $file = $request->file($berkasName);
                $extension = $file->getClientOriginalExtension();
                $safeName  = preg_replace('/[^A-Za-z0-9\-]/', '', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $fileName  = time() . '_' . $safeName . '.' . $extension;
                $file->move($uploadPath, $fileName);

                $berkasFiles[$berkas['name']] = [
                    'label'    => $berkas['label'],
                    'filename' => $fileName,
                ];
            }
        }

        // Simpan pengajuan
        $pengajuan = PengajuanBerkas::create([
            'seleksi_berkas_id' => $seleksi->id,
            'nama_pengaju'      => $request->nama_pengaju,
            'no_hp'             => $request->no_hp,
            'data_isian'        => $dataIsian,
            'berkas_files'      => $berkasFiles,
            'status'            => 'menunggu',
        ]);

        return redirect()->route('seleksi_berkas.sukses', ['slug' => $slug, 'tiket' => $pengajuan->kode_tiket]);
    }

    /**
     * Halaman sukses setelah submit.
     */
    public function sukses($slug, Request $request)
    {
        $seleksi  = SeleksiBerkas::where('slug', $slug)->firstOrFail();
        $kode     = $request->query('tiket');
        $pengajuan = PengajuanBerkas::where('kode_tiket', $kode)
            ->where('seleksi_berkas_id', $seleksi->id)
            ->firstOrFail();

        return view('frontend.seleksi_berkas.sukses', compact('seleksi', 'pengajuan'));
    }

    /**
     * Halaman cek tiket (form input kode).
     */
    public function cekTiket(Request $request)
    {
        $pengajuan = null;
        $kode      = $request->query('kode');

        if ($kode) {
            $pengajuan = PengajuanBerkas::with('seleksiBerkas')
                ->where('kode_tiket', strtoupper(trim($kode)))
                ->first();
        }

        return view('frontend.seleksi_berkas.cek_tiket', compact('pengajuan', 'kode'));
    }
}
