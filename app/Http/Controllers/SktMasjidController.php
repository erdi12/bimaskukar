<?php

namespace App\Http\Controllers;

use App\Exports\SktMasjidExport;
use App\Imports\SktMasjidImport;
use App\Models\Kecamatan;
use App\Models\SktMasjid;
use App\Models\TipologiMasjid;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SktMasjidController extends Controller
{
    private const UPLOAD_PATH = [
        'barcode' => 'app/public/masjid_barcodes',
        'skt' => 'app/public/masjid_skt',
    ];

    /**
     * Handle file upload.
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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid', 'marbots'])
                ->select('skt_masjids.*');

            if ($request->has('kecamatan_id') && $request->kecamatan_id != '') {
                $data->where('kecamatan_id', $request->kecamatan_id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('lokasi', function ($row) {
                    $kec = $row->kecamatan->kecamatan ?? '-';
                    $kel = $row->kelurahan->nama_kelurahan ?? '-';

                    return $kel.', '.$kec;
                })
                ->addColumn('tipologi', function ($row) {
                    return $row->tipologiMasjid->nama_tipologi ?? '-';
                })
                ->addColumn('marbot', function ($row) {
                    return $row->marbots->pluck('nama_lengkap')->implode(', ') ?: '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';

                    $btn .= '<a href="'.route('skt_masjid.show', $row->uuid).'" class="btn btn-sm btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>';
                    $btn .= '<div class="btn-group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Cetak">';
                    $btn .= '<i class="fas fa-print"></i>';
                    $btn .= '</button>';
                    $btn .= '<div class="dropdown-menu">';
                    $btn .= '<a class="dropdown-item" href="'.route('skt_masjid.cetak_skt', $row->uuid).'" target="_blank">Cetak SKT</a>';
                    $btn .= '<a class="dropdown-item" href="'.route('skt_masjid.cetak_rekomendasi', $row->uuid).'" target="_blank">Cetak Rekomendasi Bantuan</a>';
                    $btn .= '</div>';
                    $btn .= '</div>';

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<button onclick="editData(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>';
                    }

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<button onclick="deleteData(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    // Viewer hanya bisa melihat (jika nanti ada tombol show modal/detail)
                    // Saat ini editData juga berfungsi sebagai show di modal

                    $btn .= '</div>';

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<form id="delete-form-'.$row->uuid.'" action="'.route('skt_masjid.destroy', $row->uuid).'" method="POST" style="display:none;">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '</form>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        $tipologis = TipologiMasjid::all();

        return view('backend.skt_masjid.index', compact('kecamatans', 'tipologis'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Editor') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        $validatedData = $request->validate([
            'nama_masjid' => 'required|string|max:255',
            'nomor_id_masjid' => 'nullable|string|max:50',
            'alamat_masjid' => 'nullable|string',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'tipologi_masjid_id' => 'required|exists:tipologi_masjids,id',
            'file_barcode_masjid' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('file_barcode_masjid')) {
            $validatedData['file_barcode_masjid'] = $this->handleFileUpload(
                $request->file('file_barcode_masjid'),
                'barcode'
            );
        }

        SktMasjid::create($validatedData);

        return response()->json(['success' => 'Data Masjid berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $data = SktMasjid::where('uuid', $id)->firstOrFail();

        return response()->json($data);
    }

    public function show($id)
    {
        $sktMasjid = SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid', 'marbots'])->where('uuid', $id)->firstOrFail();

        return view('backend.skt_masjid.show', compact('sktMasjid'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Editor') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        $request->validate([
            'nama_masjid' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'tipologi_masjid_id' => 'required|exists:tipologi_masjids,id',
            'file_barcode_masjid' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = SktMasjid::where('uuid', $id)->firstOrFail();
        $input = $request->except(['file_barcode_masjid']);

        if ($request->hasFile('file_barcode_masjid')) {
            $input['file_barcode_masjid'] = $this->handleFileUpload(
                $request->file('file_barcode_masjid'),
                'barcode',
                $data->file_barcode_masjid
            );
        }

        $data->update($input);

        return response()->json(['success' => 'Data Masjid berhasil diperbarui']);
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        try {
            $data = SktMasjid::where('uuid', $id)->firstOrFail();
            if ($data->file_barcode_masjid) {
                Storage::delete('public/masjid_barcodes/'.$data->file_barcode_masjid);
            }
            $data->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

    public function cetakSkt($id)
    {
        try {
            $sktMasjid = SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid'])->where('uuid', $id)->firstOrFail();

            // Catat aktivitas cetak SKT Masjid
            activity()
                ->performedOn($sktMasjid)
                ->causedBy(auth()->user())
                ->event('cetak_skt_masjid')
                ->log('Mencetak SKT Masjid');

            $logoPath = public_path('images/kemenag/kemenag.png');
            $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

            // Setup additional image
            $additionalImageBase64 = null;
            $barcodePath = null;

            if ($sktMasjid->file_barcode_masjid) {
                $barcodePath = storage_path('app/public/masjid_barcodes/'.$sktMasjid->file_barcode_masjid);
            }

            if ($barcodePath && file_exists($barcodePath)) {
                $additionalImageBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($barcodePath));
            } else {
                // Fallback to logo if no barcode or file missing
                $additionalImagePath = public_path('images/kemenag/kemenag.png');
                if (file_exists($additionalImagePath)) {
                    $additionalImageBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($additionalImagePath));
                }
            }

            // Setup variables for the view
            $data = [
                'sktMasjid' => $sktMasjid,
                'logoBase64' => $logoBase64,
                'additionalImageBase64' => $additionalImageBase64,
                'nomor_naskah' => $sktMasjid->nomor_id_masjid, // Assuming this is the number
                'tanggal_naskah' => \Carbon\Carbon::now()->isoFormat('D MMMM Y'),
                'nama_pengirim' => 'Ariyadi F, S.Ag.',
                'nip_pengirim' => '19770805 199803 1 003',
                'jabatan_pengirim' => 'Kepala Kantor Kementerian Agama Kabupaten Kutai Kartanegara',
                'ttd_pengirim' => 'Ariyadi F, S.Ag.',
            ];

            // Generate PDF menggunakan DomPDF
            $pdf = Pdf::loadView('backend.skt_masjid.cetak_skt', $data)
                ->setPaper('A4')
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('chroot', public_path());

            // Register custom font ke DomPDF if exists
            $fontPath = public_path('fonts/imprint-mt-shadow.ttf');
            if (file_exists($fontPath)) {
                $pdf->getDomPDF()->getOptions()->set('fontDir', public_path('fonts'));
                $pdf->getDomPDF()->getOptions()->set('fontCache', storage_path('logs'));
            }

            // Buat nama file
            $fileName = $sktMasjid->nomor_id_masjid.' - '.$sktMasjid->nama_masjid.' - SKT.pdf';
            $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $fileName);

            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: '.$e->getMessage());

            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF: '.$e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function cetakRekomendasi($id)
    {
        try {
            $sktMasjid = SktMasjid::with(['kecamatan', 'kelurahan', 'tipologiMasjid'])->where('uuid', $id)->firstOrFail();

            // Catat aktivitas cetak Rekomendasi Masjid
            activity()
                ->performedOn($sktMasjid)
                ->causedBy(auth()->user())
                ->event('cetak_rekomendasi_masjid')
                ->log('Mencetak Rekomendasi Masjid');

            $logoPath = public_path('images/kemenag/kemenag.png');
            $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

            // Setup additional image
            $additionalImageBase64 = null;
            $barcodePath = null;

            if ($sktMasjid->file_barcode_masjid) {
                $barcodePath = storage_path('app/public/masjid_barcodes/'.$sktMasjid->file_barcode_masjid);
            }

            if ($barcodePath && file_exists($barcodePath)) {
                $additionalImageBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($barcodePath));
            } else {
                // Fallback to logo if no barcode or file missing
                $additionalImagePath = public_path('images/kemenag/kemenag.png');
                if (file_exists($additionalImagePath)) {
                    $additionalImageBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($additionalImagePath));
                }
            }

            // Setup variables for the view
            $data = [
                'sktMasjid' => $sktMasjid,
                'logoBase64' => $logoBase64,
                'additionalImageBase64' => $additionalImageBase64,
                'nomor_naskah' => $sktMasjid->nomor_id_masjid, // Bisa disesuaikan kalau format nomornya beda
                'tanggal_naskah' => \Carbon\Carbon::now()->isoFormat('D MMMM Y'),
                'nama_pengirim' => 'Ariyadi F, S.Ag.',
                'nip_pengirim' => '19770805 199803 1 003',
                'jabatan_pengirim' => 'Kepala Kantor Kementerian Agama Kabupaten Kutai Kartanegara',
                'ttd_pengirim' => 'Ariyadi F, S.Ag.',
            ];

            // Generate PDF menggunakan DomPDF
            $pdf = Pdf::loadView('backend.skt_masjid.cetak_rekomendasi', $data)
                ->setPaper('A4')
                ->setOption('margin-top', 10)
                ->setOption('margin-right', 10)
                ->setOption('margin-bottom', 10)
                ->setOption('margin-left', 10)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('chroot', public_path());

            // Register custom font ke DomPDF if exists
            $fontPath = public_path('fonts/imprint-mt-shadow.ttf');
            if (file_exists($fontPath)) {
                $pdf->getDomPDF()->getOptions()->set('fontDir', public_path('fonts'));
                $pdf->getDomPDF()->getOptions()->set('fontCache', storage_path('logs'));
            }

            // Buat nama file
            $fileName = $sktMasjid->nomor_id_masjid.' - '.$sktMasjid->nama_masjid.' - Rekomendasi.pdf';
            $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $fileName);

            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: '.$e->getMessage());

            return response()->view('errors.custom', [
                'message' => 'Gagal membuat PDF: '.$e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    public function uploadSkt(Request $request)
    {
        $request->validate([
            'skt_file' => 'required|mimes:pdf|max:5120', // Max 5MB
            'skt_id' => 'required|exists:skt_masjids,uuid',
        ]);

        try {
            $sktMasjid = SktMasjid::where('uuid', $request->skt_id)->firstOrFail();

            if ($request->hasFile('skt_file')) {
                $filename = $this->handleFileUpload(
                    $request->file('skt_file'),
                    'skt',
                    $sktMasjid->file_skt
                );

                $sktMasjid->update(['file_skt' => $filename]);

                return redirect()->back()->with('success', 'File SKT berhasil diupload!');
            }

            return redirect()->back()->with('error', 'Tidak ada file yang diupload.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload file: '.$e->getMessage());
        }
    }

    public function deleteSkt($id)
    {
        try {
            $sktMasjid = SktMasjid::where('uuid', $id)->firstOrFail();

            if ($sktMasjid->file_skt) {
                $filePath = storage_path('app/public/masjid_skt/'.$sktMasjid->file_skt);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $sktMasjid->update(['file_skt' => null]);

                return redirect()->back()->with('success', 'File SKT berhasil dihapus!');
            }

            return redirect()->back()->with('error', 'File tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: '.$e->getMessage());
        }
    }

    public function rekap()
    {
        $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        $tipologis = TipologiMasjid::all();

        $rekap = Kecamatan::leftJoin('skt_masjids', 'kecamatans.id', '=', 'skt_masjids.kecamatan_id')
            ->select('kecamatans.id', 'kecamatans.kecamatan as nama')
            ->selectRaw('count(skt_masjids.id) as total');

        foreach ($tipologis as $t) {
            $rekap->selectRaw("count(case when skt_masjids.tipologi_masjid_id = {$t->id} then 1 end) as count_{$t->id}");
        }

        $rekap = $rekap->groupBy('kecamatans.id', 'kecamatans.kecamatan')
            ->orderBy('kecamatans.kecamatan')
            ->get();

        return view('backend.skt_masjid.rekap', compact('rekap', 'tipologis'));
    }

    public function export()
    {
        return Excel::download(new SktMasjidExport, 'data-masjid.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xls,xlsx',
        ]);

        try {
            Excel::import(new SktMasjidImport, $request->file('file_excel'));

            return redirect()->back()->with('success', 'Data Masjid berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\SktMasjidTemplate, 'template-data-masjid.xlsx');
    }
}
