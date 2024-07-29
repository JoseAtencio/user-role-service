<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Enums\PermissionEnum;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(ActivityTableSeeder::class);
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles()
    {
        $rolesPermissions = [
            'Owner' => Permission::all(),
            'Admin' => Permission::all()->filter(function ($permission) {
                return $permission->code !== PermissionEnum::DELETE_USERS->value;
            }),
            'Assistant' => Permission::all()->filter(function ($permission) {
                return !str_contains($permission->code, 'edit_users') &&
                       !str_contains($permission->code, 'delete_users') &&
                       !str_contains($permission->code, 'edit_enterprise') &&
                       !str_contains($permission->code, 'delete_enterprise');
            }),
            'Guest' => Permission::all()->filter(function ($permission) {
                return str_contains($permission->code, 'view_users') ||
                       str_contains($permission->code, 'view_enterprise') ||
                       str_contains($permission->code, 'create_notifies');
            }),
            'No Access' => collect(),
        ];

        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            $role->permissions()->sync($permissions);
        }
    }
}