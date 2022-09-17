<?php

namespace App\Http\Controllers\API\Team;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\API\Help\HelpController;
use App\Http\Resources\TeamBlockResource;
use App\Models\Team;
use App\Models\TeamBlock;
use App\Models\User;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class TeamBlockController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $team_id)
    {
        $result = TeamBlock::where('team_id', $team_id)
            ->with(['user'])
            ->get();

        return TeamBlockResource::collection($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $team_id)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);

        $input = $request->all();

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }

        try {
            $team = Team::find($team_id);
            if (empty($team)) {
                return $this->sendError('Team Not Found!');
            }
            // check permission
            $help = new HelpController();
            $dataCheckPermission = $help->getPermissionRole($team->id);
            if (!in_array('team_member_management', $dataCheckPermission)) {
                return $this->sendError('User does not have permission.');
            }

            $checkTeamMember = TeamMember::where('user_id', $input['user_id'])
                ->where('team_id', $team->id)
                ->first();
            if (empty($checkTeamMember)) {
                return $this->sendError('User is not in team.');
            }
            $checkTeamMember->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred');
        }

        try {
            $teamBlock = TeamBlock::create([
                'team_id' => $team_id,
                'user_id' => $input['user_id'],
            ]);
        } catch (\Throwable $th) {
            return $this->sendError([], 'Cannot block user');
        }
        return response()->json(['success' => true], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($team_id, $user_id)
    {
        $team = null;

        $team = Team::find($team_id);

        if (empty($team)) {
            return $this->sendError('Team Not Found!');
        }

        $help = new HelpController();
        $dataCheckPermission = $help->getPermissionRole($team->id);
        if (!in_array('team_member_management', $dataCheckPermission)) {
            return $this->sendError('User does not have permission.');
        }
        $teamBlock = TeamBlock::where('team_id', $team_id)
            ->where('user_id', $user_id)
            ->delete();
        if ($teamBlock) {
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 404);
        }
    }
}
