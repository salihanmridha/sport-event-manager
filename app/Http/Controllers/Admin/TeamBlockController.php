<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamBlock;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class TeamBlockController extends Controller
{
    public function index(Team $team)
    {
        abort_if(Gate::denies('team_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.team-block.index',compact('team'));
    }

    public function create(Team $team)
    {
        abort_if(Gate::denies('team_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.team-block.create');
    }
}
