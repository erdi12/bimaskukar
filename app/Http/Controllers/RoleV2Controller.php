<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class RoleV2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Kita load role beserta jumlah usernya
            $data = Role::withCount('users')->select('roles.*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function($row){
                    // Badge warna-warni untuk role khusus
                    $color = match($row->name) {
                        'Admin' => 'primary',
                        'Operator' => 'info',
                        'Editor' => 'success',
                        default => 'secondary'
                    };
                    return '<span class="badge bg-'.$color.' rounded-pill">'.$row->name.'</span>';
                })
                ->addColumn('users_count', function($row){
                     return '<span class="badge bg-dark rounded-pill">'.$row->users_count.' User</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group">';
                    $btn .= '<button onclick="editRole('.$row->id.')" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>';
                    
                    // Proteksi: Role 'Admin' atau role dengan ID tertentu tidak boleh dihapus jika itu core system
                    if($row->name !== 'Admin') {
                        $btn .= '<button onclick="deleteRole('.$row->id.')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }
                    
                    $btn .= '</div>';
                    
                    // Form Delete (Hidden)
                    $btn .= '<form id="delete-form-'.$row->id.'" action="'.route('roles_v2.destroy', $row->id).'" method="POST" style="display:none;">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '</form>';
                    
                    return $btn;
                })
                ->rawColumns(['name', 'users_count', 'action'])
                ->make(true);
        }
        
        return view('backend.role_v2.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.unique' => 'Nama Role sudah ada',
        ]);

        Role::create($request->only('name', 'description'));

        return response()->json(['success' => 'Role berhasil ditambahkan']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles', 'name')->ignore($id)],
            'description' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.unique' => 'Nama Role sudah ada',
        ]);

        $role = Role::findOrFail($id);
        
        // Cegah ganti nama role Admin untuk safety, tapi boleh ganti deskripsi
        if($role->name === 'Admin' && $request->name !== 'Admin') {
            return response()->json(['errors' => ['name' => ['Role Admin tidak boleh diubah namanya.']]], 422);
        }

        $role->update($request->only('name', 'description'));

        return response()->json(['success' => 'Role berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::withCount('users')->findOrFail($id);

        if ($role->name === 'Admin') {
            return redirect()->back()->with('error', 'Role Admin System tidak dapat dihapus!');
        }

        if ($role->users_count > 0) {
           return redirect()->back()->with('error', 'Gagal membunuh role: Masih ada '.$role->users_count.' user yang menggunakan role ini. Silakan pindahkan user terlebih dahulu.');
        }

        $role->delete();

        return redirect()->back()->with('success', 'Role berhasil dihapus');
    }
}
