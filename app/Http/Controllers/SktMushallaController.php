<?php

namespace App\Http\Controllers;

use App\Exports\SktMushallaExport;
use App\Exports\SktMushallaTemplate;
use App\Imports\SktMushallaImport;
use App\Models\Kecamatan;
use App\Models\SktMushalla;
use App\Models\TipologiMushalla;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SktMushallaController extends Controller
{
    private const UPLOAD_PATH = [
        'barcode' => 'app/public/mushalla_barcodes',
        'skt' => 'app/public/mushalla_skt',
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

    public function rekap()
    {
        $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        $tipologis = TipologiMushalla::all();

        $rekap = Kecamatan::leftJoin('skt_mushallas', 'kecamatans.id', '=', 'skt_mushallas.kecamatan_id')
            ->select('kecamatans.id', 'kecamatans.kecamatan as nama')
            ->selectRaw('count(skt_mushallas.id) as total');

        foreach ($tipologis as $t) {
            $rekap->selectRaw("count(case when skt_mushallas.tipologi_mushalla_id = {$t->id} then 1 end) as count_{$t->id}");
        }

        $rekap = $rekap->groupBy('kecamatans.id', 'kecamatans.kecamatan')
            ->orderBy('kecamatans.kecamatan')
            ->get();

        return view('backend.skt_mushalla.rekap', compact('rekap', 'tipologis'));
    }

    public function export()
    {
        return Excel::download(new SktMushallaExport, 'data-mushalla.xlsx');
    }

    public function downloadTemplate()
    {
        return Excel::download(new SktMushallaTemplate, 'template-data-mushalla.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xls,xlsx',
        ]);

        try {
            Excel::import(new SktMushallaImport, $request->file('file_excel'));

            return redirect()->back()->with('success', 'Data Mushalla berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: '.$e->getMessage());
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SktMushalla::with(['kecamatan', 'kelurahan', 'tipologiMushalla'])
                ->select('skt_mushallas.*')
                ->latest();

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
                    return $row->tipologiMushalla->nama_tipologi ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';

                    $btn .= '<a href="'.route('skt_mushalla.show', $row->uuid).'" class="btn btn-sm btn-outline-info" title="Detail"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="'.route('skt_mushalla.cetak_skt', $row->uuid).'" class="btn btn-sm btn-outline-success" target="_blank" title="Cetak SKT"><i class="fas fa-print"></i></a>';

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Editor') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<button onclick="editData(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>';
                    }

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<button onclick="deleteData(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }

                    $btn .= '</div>';

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<form id="delete-form-'.$row->uuid.'" action="'.route('skt_mushalla.destroy', $row->uuid).'" method="POST" style="display:none;">';
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
        $tipologis = TipologiMushalla::all();

        return view('backend.skt_mushalla.index', compact('kecamatans', 'tipologis'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Editor') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        $validatedData = $request->validate([
            'nama_mushalla' => 'required|string|max:255',
            'nomor_id_mushalla' => 'nullable|string|max:50',
            'alamat_mushalla' => 'nullable|string',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'tipologi_mushalla_id' => 'required|exists:tipologi_mushallas,id',
            'file_barcode_mushalla' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('file_barcode_mushalla')) {
            $validatedData['file_barcode_mushalla'] = $this->handleFileUpload(
                $request->file('file_barcode_mushalla'),
                'barcode'
            );
        }

        SktMushalla::create($validatedData);

        return response()->json(['success' => 'Data Mushalla berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $data = SktMushalla::where('uuid', $id)->firstOrFail();

        return response()->json($data);
    }

    public function show($id)
    {
        $sktMushalla = SktMushalla::with(['kecamatan', 'kelurahan', 'tipologiMushalla'])->where('uuid', $id)->firstOrFail();

        return view('backend.skt_mushalla.show', compact('sktMushalla'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Editor') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        $request->validate([
            'nama_mushalla' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'tipologi_mushalla_id' => 'required|exists:tipologi_mushallas,id',
            'file_barcode_mushalla' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = SktMushalla::where('uuid', $id)->firstOrFail();
        $input = $request->except(['file_barcode_mushalla']);

        if ($request->hasFile('file_barcode_mushalla')) {
            $input['file_barcode_mushalla'] = $this->handleFileUpload(
                $request->file('file_barcode_mushalla'),
                'barcode',
                $data->file_barcode_mushalla
            );
        }

        $data->update($input);

        return response()->json(['success' => 'Data Mushalla berhasil diperbarui']);
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        try {
            $data = SktMushalla::where('uuid', $id)->firstOrFail();
            if ($data->file_barcode_mushalla) {
                Storage::delete('public/mushalla_barcodes/'.$data->file_barcode_mushalla);
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
            $sktMushalla = SktMushalla::with(['kecamatan', 'kelurahan', 'tipologiMushalla'])->where('uuid', $id)->firstOrFail();

            // Catat aktivitas cetak SKT Mushalla
            activity()
                ->performedOn($sktMushalla)
                ->causedBy(auth()->user())
                ->event('cetak_skt_mushalla')
                ->log('Mencetak SKT Mushalla');

            $logoPath = public_path('images/kemenag/kemenag.png');
            $logoBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath));

            // Setup additional image
            $additionalImageBase64 = null;
            $barcodePath = null;

            if ($sktMushalla->file_barcode_mushalla) {
                $barcodePath = storage_path('app/public/mushalla_barcodes/'.$sktMushalla->file_barcode_mushalla);
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
                'sktMushalla' => $sktMushalla,
                'logoBase64' => $logoBase64,
                'additionalImageBase64' => $additionalImageBase64,
                'nomor_naskah' => $sktMushalla->nomor_id_mushalla,
                'tanggal_naskah' => \Carbon\Carbon::now()->isoFormat('D MMMM Y'),
                'nama_pengirim' => 'Ariyadi F, S.Ag.',
                'nip_pengirim' => '19770805 199803 1 003',
                'jabatan_pengirim' => 'Kepala Kantor Kementerian Agama Kabupaten Kutai Kartanegara',
                'ttd_pengirim' => 'Ariyadi F, S.Ag.',
            ];

            // Generate PDF menggunakan DomPDF
            $pdf = Pdf::loadView('backend.skt_mushalla.cetak_skt', $data)
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
            $fileName = $sktMushalla->nomor_id_mushalla.' - '.$sktMushalla->nama_mushalla.' - SKT.pdf';
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
            'skt_id' => 'required|exists:skt_mushallas,uuid',
        ]);

        try {
            $sktMushalla = SktMushalla::where('uuid', $request->skt_id)->firstOrFail();

            if ($request->hasFile('skt_file')) {
                $filename = $this->handleFileUpload(
                    $request->file('skt_file'),
                    'skt',
                    $sktMushalla->file_skt
                );

                $sktMushalla->update(['file_skt' => $filename]);

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
            $sktMushalla = SktMushalla::where('uuid', $id)->firstOrFail();

            if ($sktMushalla->file_skt) {
                $filePath = storage_path('app/public/mushalla_skt/'.$sktMushalla->file_skt);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $sktMushalla->update(['file_skt' => null]);

                return redirect()->back()->with('success', 'File SKT berhasil dihapus!');
            }

            return redirect()->back()->with('error', 'File tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus file: '.$e->getMessage());
        }
    }
}
