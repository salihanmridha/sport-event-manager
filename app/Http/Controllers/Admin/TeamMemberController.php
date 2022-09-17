<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Team;
use App\Models\TeamMember;
use Gate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamMemberController extends Controller
{
    public function edit(Team $team, TeamMember $member)
    {
        abort_if(Gate::denies('team_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.team.member.edit', compact('team', 'member'));
    }
    public function listMemberRequest(Team $team)
    {
        abort_if(Gate::denies('team_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.team.member-request', compact('team'));
    }
}
