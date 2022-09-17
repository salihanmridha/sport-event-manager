<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PermissionRole;

class AppsPermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appsPermission = ['team_settings_management', 'team_member_management', 'team_event_management', 'team_ownership_management', 'team_termination', 'team_post_management'];
        $roles = ['Team Owner', 'Team Manager'];
        $allow_permission_team_manager = ['team_event_management'];
        
        foreach ($roles as $role_name) {
            $roleModel = Role::where('title' , '=', $role_name)->first();
            if ($roleModel) {
                foreach ($appsPermission as $permission_name) {
                    if ($role_name == 'Team Manager' && !in_array($permission_name, $allow_permission_team_manager)) {
                      continue;
                    }
                    $permissionModel = Permission::where('title' , '=', $permission_name)->where('type', '=', 1)->first();
                    if ($permissionModel) {
                        PermissionRole::firstOrCreate(
                            [
                                'role_id' => $roleModel->id,
                                'permission_id' => $permissionModel->id,
                            ],
                            [
                                'role_id' => $roleModel->id,
                                'permission_id' => $permissionModel->id,
                            ]
                        );
                    }
                }
            }   
        }  
    }
}
