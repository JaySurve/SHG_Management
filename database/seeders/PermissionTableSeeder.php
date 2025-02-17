<?php

namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'Member-list',
            'Member-create',
            'Member-edit',
            'Member-delete',
            'Group-list',
            'Group-create',
            'Group-edit',
            'Group-delete',
            'Savings-list',
            'Savings-create',
            'Savings-edit',
            'Savings-delete',
            'User-list',
            'User-create',
            'User-edit',
            'User-delete',
            'Iga-list',
            'Iga-create',
            'Iga-edit',
            'Iga-delete',
            'Training-list',
            'Training-create',
            'Training-edit',
            'Training-delete',
            'Meetings-list',
            'Meetings-create',
            'Meetings-edit',
            'Meetings-delete',
        ];
        
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}