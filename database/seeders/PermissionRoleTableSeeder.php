<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_' && substr($permission->title, 0, 5) != 'role_' && substr($permission->title, 0, 11) != 'permission_';
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);


        // Role Venue
        $venuePermission = $admin_permissions->filter(function ($permission) {
            return (substr($permission->title, 0, 6) == 'venue_' && substr($permission->title, -4) == '_own')
                || substr($permission->title, 0, 5) == 'auth_';
        });
        Role::findOrFail(3)->permissions()->sync($venuePermission);
    }
}
