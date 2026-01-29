<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KelurahanV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kelurahan::with('kecamatan')->select('kelurahans.*');

            // Filter by Kecamatan
            if ($request->has('kecamatan_id') && $request->kecamatan_id != '') {
                $data->where('kecamatan_id', $request->kecamatan_id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_kecamatan', function ($row) {
                    return $row->kecamatan ? $row->kecamatan->kecamatan : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';
                    // Tombol Show (All)
                    $btn .= '<button onclick="showKelurahan(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-info" title="Lihat Detail"><i class="fas fa-eye"></i></button>';

                    // Tombol Edit (Admin, Operator, Editor)
                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator') || auth()->user()->hasRole('Editor')) {
                        $btn .= '<button onclick="editKelurahan(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>';
                    }

                    // Tombol Delete (Admin, Operator)
                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<button onclick="deleteKelurahan(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }
                    $btn .= '</div>';

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<form id="delete-form-'.$row->uuid.'" action="'.route('kelurahan_v2.destroy', $row->uuid).'" method="POST" style="display:none;">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '</form>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $kecamatans = Kecamatan::orderBy('kecamatan', 'asc')->get();

        return view('backend.kelurahan_v2.index', compact('kecamatans'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelurahan = Kelurahan::with(['kecamatan'])->withCount('sktpiagammts')->where('uuid', $id)->firstOrFail();

        return response()->json($kelurahan);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama_kelurahan' => 'required|string|max:255|unique:kelurahans,nama_kelurahan',
            'jenis_kelurahan' => 'required|in:Desa,Kelurahan',
        ], [
            'kecamatan_id.required' => 'Kecamatan harus dipilih',
            'nama_kelurahan.required' => 'Nama Kelurahan harus diisi',
            'nama_kelurahan.unique' => 'Nama Kelurahan sudah terdaftar',
            'jenis_kelurahan.required' => 'Jenis Kelurahan harus dipilih',
        ]);

        $data = $request->only('kecamatan_id', 'nama_kelurahan', 'jenis_kelurahan');
        $data['nama_kelurahan'] = \Illuminate\Support\Str::title($data['nama_kelurahan']);

        Kelurahan::create($data);

        return response()->json(['success' => 'Data Kelurahan berhasil ditambahkan']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kelurahan = Kelurahan::where('uuid', $id)->firstOrFail();

        return response()->json($kelurahan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Operator') && ! auth()->user()->hasRole('Editor')) {
            abort(403);
        }
        $kelurahan = Kelurahan::where('uuid', $id)->firstOrFail();

        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama_kelurahan' => 'required|string|max:255|unique:kelurahans,nama_kelurahan,'.$kelurahan->id,
            'jenis_kelurahan' => 'required|in:Desa,Kelurahan',
        ], [
            'kecamatan_id.required' => 'Kecamatan harus dipilih',
            'nama_kelurahan.required' => 'Nama Kelurahan harus diisi',
            'nama_kelurahan.unique' => 'Nama Kelurahan sudah terdaftar',
            'jenis_kelurahan.required' => 'Jenis Kelurahan harus dipilih',
        ]);

        $data = $request->only('kecamatan_id', 'nama_kelurahan', 'jenis_kelurahan');
        $data['nama_kelurahan'] = \Illuminate\Support\Str::title($data['nama_kelurahan']);

        $kelurahan->update($data);

        return response()->json(['success' => 'Data Kelurahan berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Operator')) {
            abort(403);
        }
        try {
            Kelurahan::where('uuid', $id)->firstOrFail()->delete();

            return redirect()->back()->with('success', 'Data Kelurahan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
