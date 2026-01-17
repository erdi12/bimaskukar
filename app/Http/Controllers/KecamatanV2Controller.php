<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KecamatanV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kecamatan::orderBy('kecamatan', 'asc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';
                    // Tombol Show (All Roles, including Viewer)
                    $btn .= '<button onclick="showKecamatan(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-info" title="Lihat Detail"><i class="fas fa-eye"></i></button>';

                    // Tombol Edit (Admin, Operator, Editor)
                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator') || auth()->user()->hasRole('Editor')) {
                        $btn .= '<button onclick="editKecamatan(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>';
                    }

                    // Tombol Delete (Admin, Operator)
                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<button onclick="deleteKecamatan(\''.$row->uuid.'\')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }
                    $btn .= '</div>';

                    if (auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Operator')) {
                        $btn .= '<form id="delete-form-'.$row->uuid.'" action="'.route('kecamatan_v2.destroy', $row->uuid).'" method="POST" style="display:none;">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '</form>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.kecamatan_v2.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kecamatan = Kecamatan::withCount(['kelurahans', 'sktpiagammts'])->where('uuid', $id)->firstOrFail();

        return response()->json($kecamatan);
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
            'kecamatan' => 'required|string|max:255|unique:kecamatans,kecamatan',
        ], [
            'kecamatan.required' => 'Nama Kecamatan harus diisi',
            'kecamatan.unique' => 'Nama Kecamatan sudah terdaftar',
        ]);

        Kecamatan::create($request->only('kecamatan'));

        return response()->json(['success' => 'Data Kecamatan berhasil ditambahkan']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kecamatan = Kecamatan::where('uuid', $id)->firstOrFail();

        return response()->json($kecamatan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasRole('Admin') && ! auth()->user()->hasRole('Operator') && ! auth()->user()->hasRole('Editor')) {
            abort(403);
        }

        $kecamatan = Kecamatan::where('uuid', $id)->firstOrFail();

        $request->validate([
            'kecamatan' => 'required|string|max:255|unique:kecamatans,kecamatan,'.$kecamatan->id,
        ], [
            'kecamatan.required' => 'Nama Kecamatan harus diisi',
            'kecamatan.unique' => 'Nama Kecamatan sudah terdaftar',
        ]);

        $kecamatan->update($request->only('kecamatan'));

        return response()->json(['success' => 'Data Kecamatan berhasil diperbarui']);
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
            Kecamatan::where('uuid', $id)->firstOrFail()->delete();

            return redirect()->back()->with('success', 'Data Kecamatan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
