<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id' => 1,
                'title' => 'auth_profile_edit',
                'type' => 0,
            ],
            [
                'id' => 2,
                'title' => 'user_management_access',
                'type' => 0,
            ],
            [
                'id' => 3,
                'title' => 'permission_create',
                'type' => 0,
            ],
            [
                'id' => 4,
                'title' => 'permission_edit',
                'type' => 0,
            ],
            [
                'id' => 5,
                'title' => 'permission_show',
                'type' => 0,
            ],
            [
                'id' => 6,
                'title' => 'permission_delete',
                'type' => 0,
            ],
            [
                'id' => 7,
                'title' => 'permission_access',
                'type' => 0,
            ],
            [
                'id' => 8,
                'title' => 'role_create',
                'type' => 0,
            ],
            [
                'id' => 9,
                'title' => 'role_edit',
                'type' => 0,
            ],
            [
                'id' => 10,
                'title' => 'role_show',
                'type' => 0,
            ],
            [
                'id' => 11,
                'title' => 'role_delete',
                'type' => 0,
            ],
            [
                'id' => 12,
                'title' => 'role_access',
                'type' => 0,
            ],
            [
                'id' => 13,
                'title' => 'user_create',
                'type' => 0,
            ],
            [
                'id' => 14,
                'title' => 'user_edit',
                'type' => 0,
            ],
            [
                'id' => 15,
                'title' => 'user_show',
                'type' => 0,
            ],
            [
                'id' => 16,
                'title' => 'user_delete',
                'type' => 0,
            ],
            [
                'id' => 17,
                'title' => 'user_access',
                'type' => 0,
            ],
            [
                'id' => 18,
                'title' => 'sport_create',
                'type' => 0,
            ],
            [
                'id' => 19,
                'title' => 'sport_edit',
                'type' => 0,
            ],
            [
                'id' => 20,
                'title' => 'sport_show',
                'type' => 0,
            ],
            [
                'id' => 21,
                'title' => 'sport_delete',
                'type' => 0,
            ],
            [
                'id' => 22,
                'title' => 'sport_access',
                'type' => 0,
            ],
            [
                'id' => 23,
                'title' => 'team_create',
                'type' => 0,
            ],
            [
                'id' => 24,
                'title' => 'team_edit',
                'type' => 0,
            ],
            [
                'id' => 25,
                'title' => 'team_show',
                'type' => 0,
            ],
            [
                'id' => 26,
                'title' => 'team_delete',
                'type' => 0,
            ],
            [
                'id' => 27,
                'title' => 'team_access',
                'type' => 0,
            ],
            [
                'id' => 28,
                'title' => 'country_create',
                'type' => 0,
            ],
            [
                'id' => 29,
                'title' => 'country_edit',
                'type' => 0,
            ],
            [
                'id' => 30,
                'title' => 'country_show',
                'type' => 0,
            ],
            [
                'id' => 31,
                'title' => 'country_delete',
                'type' => 0,
            ],
            [
                'id' => 32,
                'title' => 'country_access',
                'type' => 0,
            ],
            [
                'id' => 33,
                'title' => 'event_create',
                'type' => 0,
            ],
            [
                'id' => 34,
                'title' => 'event_edit',
                'type' => 0,
            ],
            [
                'id' => 35,
                'title' => 'event_show',
                'type' => 0,
            ],
            [
                'id' => 36,
                'title' => 'event_delete',
                'type' => 0,
            ],
            [
                'id' => 37,
                'title' => 'event_access',
                'type' => 0,
            ],
            [
                'id' => 38,
                'title' => 'content_access',
                'type' => 0,
            ],
            [
                'id' => 39,
                'title' => 'system_access',
                'type' => 0,
            ],
            [
                'id' => 40,
                'title' => 'currency_access',
                'type' => 0,
            ],
            [
                'id' => 41,
                'title' => 'currency_create',
                'type' => 0,
            ],
            [
                'id' => 42,
                'title' => 'currency_edit',
                'type' => 0,
            ],
            [
                'id' => 43,
                'title' => 'currency_show',
                'type' => 0,
            ],
            [
                'id' => 44,
                'title' => 'currency_delete',
                'type' => 0,
            ],
            [
                'id' => 45,
                'title' => 'event_management_access',
                'type' => 0,
            ],
            [
                'id' => 46,
                'title' => 'booking_management_access',
                'type' => 0,
            ],
            [
                'id' => 47,
                'title' => 'relationship_access',
                'type' => 0,
            ],
            [
                'id' => 48,
                'title' => 'relationship_create',
                'type' => 0,
            ],
            [
                'id' => 49,
                'title' => 'relationship_edit',
                'type' => 0,
            ],
            [
                'id' => 50,
                'title' => 'relationship_show',
                'type' => 0,
            ],
            [
                'id' => 51,
                'title' => 'relationship_delete',
                'type' => 0,
            ],
            [
                'id' => 52,
                'title' => 'announcement_access',
                'type' => 0,
            ],
            [
                'id' => 53,
                'title' => 'list_player_access',
                'type' => 0,
            ],
            [
                'id' => 54,
                'title' => 'list_player_delete',
                'type' => 0,
            ],
            [
                'id' => 55,
                'title' => 'announcement_create',
                'type' => 0,
            ],
            [
                'id' => 56,
                'title' => 'announcement_edit',
                'type' => 0,
            ],
            [
                'id' => 57,
                'title' => 'announcement_show',
                'type' => 0,
            ],
            [
                'id' => 58,
                'title' => 'announcement_delete',
                'type' => 0,
            ],
            [
                'id' => 59,
                'title' => 'team_settings_management',
                'type' => 1,
            ],
            [
                'id' => 60,
                'title' => 'team_member_management',
                'type' => 1,
            ],
            [
                'id' => 61,
                'title' => 'team_event_management',
                'type' => 1,
            ],
            [
                'id' => 62,
                'title' => 'team_ownership_management',
                'type' => 1,
            ],
            [
                'id' => 63,
                'title' => 'team_termination',
                'type' => 1,
            ],
            [
                'id' => 64,
                'title' => 'team_post_management',
                'type' => 1,
            ],
            [
                'id'    => 65,
                'title' => 'venue_access',
                'type' => 0,
            ],
            [
                'id'    => 66,
                'title' => 'venue_edit',
                'type' => 0,
            ],
            [
                'id'    => 67,
                'title' => 'venue_create',
                'type' => 0,
            ],
            [
                'id'    => 68,
                'title' => 'venue_show',
                'type' => 0,
            ],
            [
                'id'    => 69,
                'title' => 'venue_delete',
                'type' => 0,
            ],
            [
                'id'    => 70,
                'title' => 'venue_edit_own',
                'type' => 0,
            ],
            [
                'id'    => 71,
                'title' => 'venue_show_own',
                'type' => 0,
            ],
            [
                'id'    => 72,
                'title' => 'venue_delete_own',
                'type' => 0,
            ],
            [
                'id'    => 73,
                'title' => 'skill_access',
                'type' => 0,
            ],
            [
                'id'    => 74,
                'title' => 'skill_show',
                'type' => 0,
            ],
            [
                'id'    => 75,
                'title' => 'skill_create',
                'type' => 0,
            ],
            [
                'id'    => 76,
                'title' => 'skill_edit',
                'type' => 0,
            ],
            [
                'id'    => 77,
                'title' => 'skill_delete',
                'type' => 0,
            ],
            [
                'id'    => 78,
                'title' => 'organization_access',
                'type' => 0,
            ],
            [
                'id'    => 79,
                'title' => 'organization_show',
                'type' => 0,
            ],
            [
                'id'    => 80,
                'title' => 'organization_create',
                'type' => 0,
            ],
            [
                'id'    => 81,
                'title' => 'organization_edit',
                'type' => 0,
            ],
            [
                'id'    => 82,
                'title' => 'organization_delete',
                'type' => 0,
            ],
            [
                'id'    => 83,
                'title' => 'venue_reservation_access_own',
                'type' => 0,
            ],
            [
                'id'    => 84,
                'title' => 'venue_reservation_response_own',
                'type' => 0,
            ],
            [
                'id'    => 85,
                'title' => 'team_member_delete',
                'type' => 0,
            ],
            [
                'id'    => 86,
                'title' => 'team_member_set_owner',
                'type' => 0,
            ],
            [
                'id'    => 87,
                'title' => 'venue_type_access',
                'type' => 0,
            ],
            [
                'id'    => 88,
                'title' => 'venue_type_show',
                'type' => 0,
            ],
            [
                'id'    => 89,
                'title' => 'venue_type_create',
                'type' => 0,
            ],
            [
                'id'    => 90,
                'title' => 'venue_type_edit',
                'type' => 0,
            ],
            [
                'id'    => 91,
                'title' => 'venue_type_delete',
                'type' => 0,
            ],
        ];
        DB::table('permissions')->upsert($permissions, 'title');
    }
}
