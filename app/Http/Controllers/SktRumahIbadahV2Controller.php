<?php

namespace App\Http\Controllers;

use App\Models\JenisRumahIbadah;
use App\Models\Kecamatan;
use App\Models\SktRumahIbadah;
use App\Models\TipologiRumahIbadah;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SktRumahIbadahV2Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SktRumahIbadah::with(['kecamatan', 'kelurahan', 'jenisrumahibadah', 'tipologirumahibadah'])
                ->select('skt_rumah_ibadahs.*');

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
                ->addColumn('jenis_tipologi', function ($row) {
                    $jenis = $row->jenisrumahibadah->jenis_rumah_ibadah ?? '-';
                    $tipe = $row->tipologirumahibadah->nama_tipologi ?? '-';

                    return '<span class="fw-bold">'.$jenis.'</span><br><small class="text-muted">'.$tipe.'</small>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';
                    $btn .= '<button onclick="editData('.$row->id.')" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>';
                    $btn .= '<button onclick="deleteData('.$row->id.')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';

                    $btn .= '<form id="delete-form-'.$row->id.'" action="'.route('rumah_ibadah_v2.destroy', $row->id).'" method="POST" style="display:none;">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '</form>';

                    return $btn;
                })
                ->rawColumns(['jenis_tipologi', 'action'])
                ->make(true);
        }

        $kecamatans = Kecamatan::orderBy('kecamatan')->get();
        $jenisRumahIbadah = JenisRumahIbadah::all();

        return view('backend.skt_rumah_ibadah_v2.index', compact('kecamatans', 'jenisRumahIbadah'));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_rumah_ibadah' => 'required|string|max:255',
            'nomor_statistik' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'jenis_rumah_ibadah_id' => 'required|exists:jenis_rumah_ibadahs,id',
            'tipologi_rumah_ibadah_id' => 'nullable|exists:tipologi_rumah_ibadahs,id',
        ]);

        SktRumahIbadah::create($request->all());

        return response()->json(['success' => 'Data Rumah Ibadah berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $data = SktRumahIbadah::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_rumah_ibadah' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'jenis_rumah_ibadah_id' => 'required|exists:jenis_rumah_ibadahs,id',
        ]);

        $data = SktRumahIbadah::findOrFail($id);
        $data->update($request->all());

        return response()->json(['success' => 'Data Rumah Ibadah berhasil diperbarui']);
    }

    public function destroy($id)
    {
        try {
            SktRumahIbadah::findOrFail($id)->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data');
        }
    }

    public function getTipologiByJenis($jenisId)
    {
        $tipologi = TipologiRumahIbadah::where('jenis_rumah_ibadah_id', $jenisId)->get();

        return response()->json($tipologi);
    }
}
