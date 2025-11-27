<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Administrator dengan akses penuh ke sistem'
            ],
            [
                'name' => 'Editor',
                'description' => 'Pengguna yang dapat mengedit data'
            ],
            [
                'name' => 'Viewer',
                'description' => 'Pengguna yang hanya dapat melihat data'
            ],
            [
                'name' => 'Operator',
                'description' => 'Pengguna operator untuk input data'
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
