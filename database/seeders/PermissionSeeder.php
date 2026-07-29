<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'user.view',
            'user.create',  
            'user.update',
            'user.delete',


            'role.view',
            'role.create',
            'role.update',  
            'role.delete',
        ];

        foreach ($permissions as $permission) {
          Permission::create(['name' => $permission]);
        }
    }
}
