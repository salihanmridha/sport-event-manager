<?php

namespace App\Http\Controllers\API\Help;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Team;
class HelpController extends BaseController
{
    public function getPermissionRole($team_id)
    {
        $user_id = auth()->user()->id;
        $response = [];
        try {
            $team = Team::find($team_id);
            $response = $team
                ->leftJoin('team_member', 'team_member.team_id', 'teams.id')
                ->leftJoin(
                    'member_role',
                    'member_role.member_id',
                    'team_member.id'
                )
                ->leftJoin(
                    'permission_role',
                    'permission_role.role_id',
                    'member_role.role_id'
                )
                ->leftJoin(
                    'permissions',
                    'permissions.id',
                    'permission_role.permission_id'
                )
                ->where('teams.id', $team_id)
                ->where('team_member.user_id', $user_id)
                ->where('team_member.team_id', $team_id)
                ->select('permissions.title')
                ->get()
                ->pluck('title')
                ->toArray();
        } catch (\Throwable $th) {
            //throw $th;
        }
        if (count($response)) {
            $response = array_filter(
                $response,
                fn($value) => !is_null($value) && $value !== ''
            );
        }
        return $response;
    }
}
