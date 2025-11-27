<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@bimas.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
            ]
        );

        // Assign role Admin
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole && !$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
            $adminUser->roles()->attach($adminRole->id);
        }

        // Buat user Editor
        $editorUser = User::firstOrCreate(
            ['email' => 'editor@bimas.test'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password123'),
            ]
        );

        // Assign role Editor
        $editorRole = Role::where('name', 'Editor')->first();
        if ($editorRole && !$editorUser->roles()->where('role_id', $editorRole->id)->exists()) {
            $editorUser->roles()->attach($editorRole->id);
        }

        // Buat user Operator
        $operatorUser = User::firstOrCreate(
            ['email' => 'operator@bimas.test'],
            [
                'name' => 'Operator User',
                'password' => Hash::make('password123'),
            ]
        );

        // Assign role Operator
        $operatorRole = Role::where('name', 'Operator')->first();
        if ($operatorRole && !$operatorUser->roles()->where('role_id', $operatorRole->id)->exists()) {
            $operatorUser->roles()->attach($operatorRole->id);
        }

        // Buat user Viewer
        $viewerUser = User::firstOrCreate(
            ['email' => 'viewer@bimas.test'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password123'),
            ]
        );

        // Assign role Viewer
        $viewerRole = Role::where('name', 'Viewer')->first();
        if ($viewerRole && !$viewerUser->roles()->where('role_id', $viewerRole->id)->exists()) {
            $viewerUser->roles()->attach($viewerRole->id);
        }

        echo "\n=== User Seed Success ===\n";
        echo "Admin User: admin@bimas.test | Password: password123\n";
        echo "Editor User: editor@bimas.test | Password: password123\n";
        echo "Operator User: operator@bimas.test | Password: password123\n";
        echo "Viewer User: viewer@bimas.test | Password: password123\n";
    }
}
