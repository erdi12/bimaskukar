<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')->orderBy('name', 'asc')->get();
        return view('backend.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.unique' => 'Nama Role sudah terdaftar',
        ]);

        Role::create($request->only('name', 'description'));

        Alert::success('Berhasil', 'Role berhasil ditambahkan');
        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::with('users')->findOrFail($id);
        $allUsers = User::orderBy('name')->get();
        return view('backend.role.show', compact('role', 'allUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return view('backend.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama Role harus diisi',
            'name.unique' => 'Nama Role sudah terdaftar',
        ]);

        $role->update($request->only('name', 'description'));

        Alert::success('Berhasil', 'Role berhasil diperbarui');
        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        Alert::success('Berhasil', 'Role berhasil dihapus');
        return redirect()->route('role.index');
    }

    /**
     * Assign role ke users
     */
    public function assignToUsers(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ], [
            'user_ids.required' => 'Pilih minimal satu user',
        ]);

        // Sync users dengan role (replace existing)
        $role->users()->sync($request->user_ids);

        Alert::success('Berhasil', 'Role berhasil diassign ke users');
        return redirect()->route('role.show', $role->id);
    }
}
