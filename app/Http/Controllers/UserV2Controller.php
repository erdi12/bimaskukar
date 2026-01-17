<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserV2Controller extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select('users.*');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function($row){
                    $roles = $row->roles->pluck('name')->map(function($role){
                        // Badge color logic
                        $color = match($role) {
                            'Admin' => 'primary',
                            'Operator' => 'info',
                            'Editor' => 'success',
                            default => 'secondary'
                        };
                        return '<span class="badge bg-'.$color.' rounded-pill">'.$role.'</span>';
                    })->implode(' ');
                    return $roles ?: '<span class="badge bg-secondary rounded-pill">User</span>';
                })
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group">';
                    $btn .= '<button onclick="editUser('.$row->id.')" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>';
                    
                    // Prevent deleting self or specific restricted users if needed
                    if(auth()->id() != $row->id) {
                         $btn .= '<button onclick="deleteUser('.$row->id.')" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }
                   
                    $btn .= '</div>';
                    
                    $btn .= '<form id="delete-form-'.$row->id.'" action="'.route('users_v2.destroy', $row->id).'" method="POST" style="display:none;">';
                    $btn .= csrf_field();
                    $btn .= method_field('DELETE');
                    $btn .= '</form>';
                    return $btn;
                })
                ->rawColumns(['role', 'action'])
                ->make(true);
        }
        
        $roles = Role::all();
        return view('backend.user_v2.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id'
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role_id.required' => 'Role wajib dipilih'
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->roles()->attach($request->role_id);
        });

        return response()->json(['success' => 'User berhasil ditambahkan']);
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        // Append first role id for easy parsing in JS
        $user->role_id = $user->roles->first()?->id; 
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed', // Nullable on update
            'role_id' => 'required|exists:roles,id'
        ]);

        DB::transaction(function() use ($request, $id) {
            $user = User::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Sync roles (replace old roles with new one)
            $user->roles()->sync([$request->role_id]);
        });

        return response()->json(['success' => 'User berhasil diperbarui']);
    }

    public function destroy($id)
    {
        if(auth()->id() == $id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        try {
            DB::transaction(function() use ($id) {
                $user = User::findOrFail($id);
                $user->roles()->detach(); // Hapus relasi role dulu
                $user->delete();
            });
            return redirect()->back()->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}
