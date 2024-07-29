<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Enums\PermissionEnum;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Permisos generales
         Permission::create(['name' => 'Send Email', 'code' => PermissionEnum::SEND_EMAIL->value]);

         Permission::create(['name' => 'View Users', 'code' => PermissionEnum::VIEW_USERS->value]);
         Permission::create(['name' => 'Create Users', 'code' => PermissionEnum::CREATE_USERS->value]);
         Permission::create(['name' => 'Edit Users', 'code' => PermissionEnum::EDIT_USERS->value]);
         Permission::create(['name' => 'Delete Users', 'code' => PermissionEnum::DELETE_USERS->value]);
 
         // Permisos para Enterprise
         Permission::create(['name' => 'View Enterprise', 'code' => PermissionEnum::VIEW_ENTERPRISE->value]);
         Permission::create(['name' => 'Create Enterprise', 'code' => PermissionEnum::CREATE_ENTERPRISE->value]);
         Permission::create(['name' => 'Edit Enterprise', 'code' => PermissionEnum::EDIT_ENTERPRISE->value]);
         Permission::create(['name' => 'Delete Enterprise', 'code' => PermissionEnum::DELETE_ENTERPRISE->value]);
        
          // Permisos para Notifies
         Permission::create(['name' => 'View Notifies', 'code' => PermissionEnum::VIEW_NOTIFIES->value]);
         Permission::create(['name' => 'Create Notifies', 'code' => PermissionEnum::CREATE_NOTIFIES->value]);
         Permission::create(['name' => 'Edit Notifies', 'code' => PermissionEnum::EDIT_NOTIFIES->value]);
         Permission::create(['name' => 'Delete Notifies', 'code' => PermissionEnum::DELETE_NOTIFIES->value]);

         // Permisos para Role
         Permission::create(['name' => 'View Roles', 'code' => PermissionEnum::VIEW_ROLES->value]);
         Permission::create(['name' => 'Create Roles', 'code' => PermissionEnum::CREATE_ROLES->value]);
         Permission::create(['name' => 'Edit Roles', 'code' => PermissionEnum::EDIT_ROLES->value]);
         Permission::create(['name' => 'Delete Roles', 'code' => PermissionEnum::DELETE_ROLES->value]);
 
         // Permisos para Abilities
         Permission::create(['name' => 'View Abilities', 'code' => PermissionEnum::VIEW_ABILITIES->value]);
         Permission::create(['name' => 'Create Abilities', 'code' => PermissionEnum::CREATE_ABILITIES->value]);
         Permission::create(['name' => 'Edit Abilities', 'code' => PermissionEnum::EDIT_ABILITIES->value]);
         Permission::create(['name' => 'Delete Abilities', 'code' => PermissionEnum::DELETE_ABILITIES->value]);
    }

    
}
