<?php

namespace App\Http\Controllers\API\Team;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\TeamLevel;
use App\Models\OrganizationRole;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\MemberRole;
use App\Models\Role;
use App\Models\Invite;
use App\Models\ContactRelationship;
use App\Models\TeamBlock;
use Validator;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamMemberResource;
use App\Http\Resources\TeamMemberDetailResource;
use App\Http\Resources\TeamRosterResource;
use App\Http\Requests\API\Team\TeamRequest;
use App\Http\Requests\API\Team\TeamUpdateRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Controllers\API\Help\HelpController;
use App\Http\Requests\API\Team\JoinTeamRequest;
use App\Models\TeamMemberRequest;
use App\Http\Resources\TeamRequestResource;
use App\Repositories\EloquentTeamRepository;
use App\Http\Resources\ListRequestResource;
use App\Http\Resources\MyTeamResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInviteMember;

const LIST_AGE = [
    0 => ['start_age' => 0, 'end_age' => 99],
    1 => ['start_age' => 0, 'end_age' => 2],
    2 => ['start_age' => 3, 'end_age' => 12],
    3 => ['start_age' => 13, 'end_age' => 19],
    4 => ['start_age' => 20, 'end_age' => 30],
    5 => ['start_age' => 31, 'end_age' => 45],
    6 => ['start_age' => 46, 'end_age' => 99],
];

class TeamController extends BaseController
{
    public function __construct(private EloquentTeamRepository $_teamRepository)
    {
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllTeamLevel(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $results = [];
        try {
            $results = TeamLevel::with(
                'user_create:email,id,first_name,last_name'
            )
                ->orderBy('updated_at', 'desc')
                ->simplePaginate($limit);
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            TeamResource::collection($results),
            'success'
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailTeamLevel($id)
    {
        $results = [];
        try {
            $results = TeamLevel::with(
                'user_create:email,id,first_name,last_name'
            )->find($id);
        } catch (\Throwable $th) {
        }
        if (empty($results)) {
            return $this->sendError('Team level not found.');
        }

        return $this->sendResponse(new TeamResource($results), 'success');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllOrganizationRole(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $results = [];
        try {
            $results = OrganizationRole::with(
                'user_create:email,id,first_name,last_name'
            )
                ->orderBy('updated_at', 'desc')
                ->simplePaginate($limit);
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            TeamResource::collection($results),
            'success'
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailOrganizationRole($id)
    {
        $results = [];
        try {
            $results = OrganizationRole::with(
                'user_create:email,id,first_name,last_name'
            )->find($id);
        } catch (\Throwable $th) {
        }
        if (empty($results)) {
            return $this->sendError('Organization role not found.');
        }

        return $this->sendResponse(new TeamResource($results), 'success');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllContactRelationship(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $results = [];
        try {
            $results = ContactRelationship::with(
                'user_create:email,id,first_name,last_name'
            )
                ->orderBy('updated_at', 'desc')
                ->simplePaginate($limit);
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            TeamResource::collection($results),
            'success'
        );
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailContactRelationship($id)
    {
        $results = [];
        try {
            $results = ContactRelationship::with(
                'user_create:email,id,first_name,last_name'
            )->find($id);
        } catch (\Throwable $th) {
        }
        if (empty($results)) {
            return $this->sendError('Organization role not found.');
        }

        return $this->sendResponse(new TeamResource($results), 'success');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createTeam(TeamRequest $teamRequest)
    {
        $input = $teamRequest->all();
        $dataInsert = [
            'name' => $input['name'],
            'sport_id' => $input['sport_id'],
            'creator_id' => auth()->user()->id,
            'gender' => isset($input['gender']) ? $input['gender'] : 'all',
            'age_group' => isset($input['age_group'])
                ? $input['age_group']
                : null,
            'bio' => isset($input['bio']) ? $input['bio'] : null,
            'organization_name' => isset($input['organization_name'])
                ? $input['organization_name']
                : null,
            'organization_website' => isset($input['organization_website'])
                ? $input['organization_website']
                : null,
            'division' => isset($input['division']) ? $input['division'] : null,
            'season' => isset($input['season']) ? $input['season'] : null,
            'level_id' => isset($input['level_id']) ? $input['level_id'] : null,
            'org_role_id' => isset($input['org_role_id'])
                ? $input['org_role_id']
                : null,
            'start_age' => isset($input['age_group'])
                ? LIST_AGE[$input['age_group']]['start_age']
                : null,
            'end_age' => isset($input['age_group'])
                ? LIST_AGE[$input['age_group']]['end_age']
                : null,
        ];
        $teamCreated = null;
        try {
            $teamCreated = Team::create($dataInsert);
        } catch (\Throwable $th) {
        }

        if (!empty($teamCreated)) {
            // CREATE USER TEAM
            try {
                $teamMember = TeamMember::create([
                    'user_id' => auth()->user()->id,
                    'team_id' => $teamCreated->id,
                    'status' => 'active',
                    'jersey_number' => null,
                    'player_role' => null,
                    'weight' => null,
                ]);
                if (!empty($teamMember)) {
                    $role_Owner = Role::where('title', 'Team Owner')->first();
                    if (!empty($role_Owner)) {
                        MemberRole::create([
                            'member_id' => $teamMember->id,
                            'role_id' => $role_Owner->id,
                        ]);
                    }
                    $role_Coach = Role::where('title', 'Team Coach')->first();
                    if ($teamRequest->has('set_coach') && $input['set_coach'] == 0 && !empty($role_Coach)) {
                        MemberRole::create([
                            'member_id' => $teamMember->id,
                            'role_id' => $role_Coach->id,
                        ]);
                    }
                }

                // CREATE MEMBER ROLE
                if ($teamRequest->has('set_coach') && in_array($input['set_coach'], [1, 2])) {
                    $dataInvite = [
                        'creator_id' => auth()->user()->id,
                        'source_id' => $teamCreated->id,
                        'source_type' => 'team',
                        'target_id' => null,
                        'target_type' => null,
                        'email ' => null,
                        'first_name ' => null,
                        'last_name ' => null,
                    ];
                    switch ($input['set_coach']) {
                        case 1:
                            $dataInvite['target_id'] = $input['invite_user'];
                            $dataInvite['target_type'] = 'user';
                            break;
                        default:
                            $dataInvite['email'] = $input['invite_email'];
                            break;
                    }
                    Invite::create($dataInvite);
                }
            } catch (\Throwable $th) {
                return $this->sendError('An error occurred.');
            }
        }
        return $this->sendResponse(new TeamResource($teamCreated), 'success');
    }

    public function getAllTeam(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $results = null;
        try {
            $results = Team::with([
                'sport:id,name,description,max_player_per_team,min_player_per_team,is_require_choose_role',
                'creator:id,email,last_name,first_name,phone,birth_date,gender',
                'team_level:id,code,name',
                'organization_role:id,code,name',
            ])->simplePaginate($limit);
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            TeamResource::collection($results),
            'success'
        );
    }

    public function searchTeamByName(Request $request)
    {
        $input = $request->all();
        $search = isset($input['q']) ? $input['q'] : '';
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $validator = Validator::make($request->all(), [
            'q' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        $results = null;
        try {
            $results = Team::Where('name', 'like', '%' . $search . '%')
                ->with([
                    'sport:id,name,description,max_player_per_team,min_player_per_team,is_require_choose_role',
                    'creator:id,email,last_name,first_name,phone,birth_date,gender',
                    'team_level:id,code,name',
                    'organization_role:id,code,name',
                ])
                ->simplePaginate($limit);
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            TeamResource::collection($results),
            'success'
        );
    }

    public function updateTeamProfile(TeamUpdateRequest $teamUpdateRequest, $id)
    {
        $input = $teamUpdateRequest->all();
        $defaultTeam = new Team();
        $dataUpdate = $defaultTeam->fill($input)->getAttributes();
        unset($dataUpdate['team_avatar_image']);
        unset($dataUpdate['team_background']);
        $team = null;

        try {
            $team = Team::find($id);
            if(empty($team)){
                return $this->sendError('Team not found.');
            }
            if(!empty($team->gender->value)){
                unset($dataUpdate['gender']);
            }
            if(!empty($team->age_group)){
                unset($dataUpdate['age_group']);
            }
            if(!empty($team->level_id)){
                unset($dataUpdate['level_id']);
            }
        } catch (\Throwable $th) {
        }
        $user = $teamUpdateRequest->user();
        $help = new HelpController();
        $dataCheckPermission = $help->getPermissionRole($id);
        if (!in_array('team_settings_management', $dataCheckPermission)) {
            return $this->sendError('User does not have permission.');
        }

        try {
            if(!empty($input['organization_website'])){
                $dataUpdate['oganization_url']  = $input['organization_website'];
            }
            if(!empty($input['organization_name'])){
                $dataUpdate['oganization_name']  = $input['organization_name'];
            }
            $dataUpdate['updated_at'] = date('Y-m-d H:i:s');
            $res = $team->update($dataUpdate);

            if ($res) {
                // UPDATE IMAGE
                $avatar = isset($input['avatar']) ? $input['avatar'] : null;
                $background_image = isset($input['background_image'])
                    ? $input['background_image']
                    : null;
                if (!empty($avatar)) {
                    $media_avatar = Media::find($avatar);
                    if(!empty($media_avatar)){
                        // remove avatar 
                        Media::where('collection_name', 'team_avatar_image')->where('model_id', $id)->where('id', '!=', $avatar)->delete();
                        $media_avatar->update([
                            'model_id' => $id,
                        ]);
                    }
                }
                if (!empty($background_image)) {
                    $media_background_image = Media::find($background_image);
                    if(!empty($media_background_image)){
                        // remove avatar 
                        Media::where('collection_name', 'team_background')->where('model_id', $id)->where('id', '!=', $background_image)->delete();
                        $media_background_image->update([
                            'model_id' => $id,
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            return $this->sendError('Update failed.');
        }
        return response()->json(['success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailTeam($id)
    {
        $user_id = auth()->user()->id;
        $results = [];
        try {
            $results = Team::with(['sport:id,name,description,max_player_per_team,min_player_per_team,is_require_choose_role',
                'creator:id,email,last_name,first_name,phone,birth_date,gender',
                'team_level:id,code,name',
                'organization_role:id,code,name']
            )
                ->with('teamMember', function ($q) {
                    $q->with('user:id,email,last_name,first_name');
                    $q->whereHas('member_role', function ($q) {
                        $q->where('title', 'Team Player');
                    });
                    $q->where('status', 'active');
                })
                ->withCount('members')
                ->withCount('userRequests')
                ->find($id);

            if (empty($results)) {
                return $this->sendError('Team not found.');
            }

            $results->team_owner = false;
            $results->status_join = null;
            if(!empty($results)){
                $checkTeamOwner = TeamMember::Where('team_id', $results->id)
                    ->where('user_id', $user_id)
                    ->with(['member_role:id,title'])
                    ->first();
                if(!empty($checkTeamOwner)){
                    $listRole = $checkTeamOwner->member_role->pluck('title')->toArray();
                    if(in_array('Team Owner',$listRole)){
                        $results->team_owner = true;
                        $results->status_join = 'accept';
                    }
                }

                if($results->status_join != 'accept'){
                    $existRequestInvites = TeamMemberRequest::where('user_id', $user_id)->where('team_id',$results->id)->first();
                    if(!empty($existRequestInvites) && is_null($existRequestInvites->response)){
                        $results->status_join = 'pending';
                    }elseif(!empty($existRequestInvites) && $existRequestInvites->response == 0){
                        $results->status_join = 'decine';
                    }elseif(!empty($existRequestInvites) && $existRequestInvites->response == 1){
                        $results->status_join = 'accept';
                    }
                    // check wait
                    $checkBlock = TeamBlock::where('user_id', $user_id)->where('team_id',$results->id)->first();
                    if(!empty($checkBlock)){
                        $results->status_join = 'blocked';
                    }
                }
            }
            
        } catch (\Throwable $th) {

        }
       

        return $this->sendResponse(new TeamResource($results), 'success');
    }

    public function getTeamProfile($team_id)
    {
        $user_id = auth()->user()->id;
        $results = [];
        try {
            $results = Team::with([
                'sport:id,name,description,max_player_per_team,min_player_per_team,is_require_choose_role',
                'creator:id,email,last_name,first_name,phone,birth_date,gender',
                'team_level:id,code,name',
                'organization_role:id,code,name']
            )
                ->with('teamMember', function ($q) {
                    $q->with('user:id,email,last_name,first_name');
                    $q->whereHas('member_role', function ($q) {
                        $q->where('title', 'Team Player');
                    });
                    $q->where('status', 'active');
                })
                ->withCount('members')
                ->withCount('userRequests')
                ->find($team_id);

            if (empty($results)) {
                return $this->sendError('Team not found.');
            }

            $results->team_owner = false;
            $results->status_join = null;
            $results->unread_message_count = null;
            if(!empty($results)){
                $checkTeamOwner = TeamMember::Where('team_id', $results->id)
                    ->where('user_id', $user_id)
                    ->with(['member_role:id,title'])
                    ->first();
                if(!empty($checkTeamOwner)){
                    $listRole = $checkTeamOwner->member_role->pluck('title')->toArray();
                    if(in_array('Team Owner',$listRole)){
                        $results->team_owner = true;
                    }
                    $results->status_join = 'accept';
                }

                if($results->status_join != 'accept'){
                    $existRequestInvites = TeamMemberRequest::where('user_id', $user_id)->where('team_id',$results->id)->first();
                    if(!empty($existRequestInvites) && is_null($existRequestInvites->response)){
                        $results->status_join = 'pending';
                    }elseif(!empty($existRequestInvites) && $existRequestInvites->response == 0){
                        $results->status_join = 'reject';
                    }elseif(!empty($existRequestInvites) && $existRequestInvites->response == 1){
                        $results->status_join = 'accept';
                    }
                    // check wait
                    $checkBlock = TeamBlock::where('user_id', $user_id)->where('team_id',$results->id)->first();
                    if(!empty($checkBlock)){
                        $results->status_join = 'blocked';
                    }
                }
            }
            
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(new TeamResource($results), 'success');
    }

    public function getProfileContent(Team $team, Request $request)
    {
        $input = $request->all();
        $union = $this->_teamRepository->infoContent($team, $input);
        return $this->sendResponse(
            $union,
            'success'
        );
    }

    public function removeTeam($id)
    {
        $team = Team::find($id);
        $user_id = auth()->user()->id;
        if (empty($team)) {
            return $this->sendError('Event Not Found!');
        }
        // check permission
        $help = new HelpController();
        $dataCheckPermission = $help->getPermissionRole($team->id);
        if (!in_array('team_settings_management', $dataCheckPermission)) {
            return $this->sendError('User does not have permission.');
        }
        // end check permission
        $format_create_time_now = date('Y-m-d H:i:s');

        $res = $team->update(['deleted_at' => $format_create_time_now]);
        if ($res) {
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 404);
        }
    }

    public function getAllTeamRequest($team_id) {
      try {
        $checkExistTeam = Team::query()->find($team_id);
        if ($checkExistTeam == null) {
            throw new Exception('This team does not exist.');
        }
        $results = null;
        try {
          $limit = (isset($_GET['perpage']) && is_numeric($_GET['perpage']) && $_GET['perpage'] ) ? intval($_GET['perpage']) : 20;
          $results = $checkExistTeam->userRequests()
          ->whereNull('team_request.response')
              ->select('users.id', 'first_name', 'last_name')
              ->withCount('memberTeams')
              ->with('sports')
              ->simplePaginate($limit);
        } catch (\Throwable $e) {
          throw new Exception($e->getMessage());          
        }

        return $this->sendResponse(
          // TeamResource::collection($results),
          //   'success'
          ListRequestResource::collection($results),
             'success'
        );
      } catch (\Throwable $th) {
        return $this->sendResponse(
            [],
            'Something went wrong. Please try it again!' . $th->getMessage()
        );
      }
    }

    public function requestJoinTeam(Request $request)
    {
        $input = $request->all();
        $dataInsert = [
            'team_id' => $input['team_id'],
            'user_id' => auth()->user()->id,
        ];
        try {
            $checkExistTeam = Team::query()->find($input['team_id']);
            if ($checkExistTeam == null) {
                return $this->sendError('This team does not exist.');
            }

            // check block
            $existTeamBlock = TeamBlock::where('user_id', auth()->user()->id)->where('team_id', $checkExistTeam->id)->first();
            if(!empty($existTeamBlock)){
                return $this->sendError('User are being blocked by team owner');
            }

            $checkExistTeamMember = TeamMember::query()
                ->where('team_id', '=', $input['team_id'])
                ->where('user_id', '=', $dataInsert['user_id'])
                ->where('deleted_at', '=', null);
            if ($checkExistTeamMember->exists()) {
                return $this->sendError('User already is a team member.');
            }

            $checkExistTeamMember = TeamMember::query()
                ->where('team_id', '=', $input['team_id'])
                ->where('user_id', '=', $dataInsert['user_id'])
                ->where('deleted_at', '!=', null);
            if ($checkExistTeamMember->exists()) {
                $teamMember = $checkExistTeamMember->update([
                    'deleted_at' => null,
                ]);
                return $this->sendResponse(
                    new TeamRequestResource($teamMember),
                    'success'
                );
            }

            $checkExistTeamRequest = TeamMemberRequest::query()
                ->where('team_id', '=', $input['team_id'])
                ->where('user_id', '=', $dataInsert['user_id'])
                ->where('response', '=', null);
            if ($checkExistTeamRequest->exists()) {
                return $this->sendError('Already submit request for membership.');

            }

            if (
                !$checkExistTeamRequest->exists() &&
                !$checkExistTeamMember->exists()
            ) {
                // REMOVE REQUEST USER
                TeamMemberRequest::where('team_id', '=', $input['team_id'])
                ->where('user_id', '=', $dataInsert['user_id'])
                ->delete();

                $teamRequest = TeamMemberRequest::create([
                    'user_id' => $dataInsert['user_id'],
                    'team_id' => $input['team_id'],
                ]);
                if(!empty($teamRequest)){
                    return response()->json(['success' => true], 200);
                }else{
                    return response()->json(['success' => false], 404);
                }
            }
        } catch (\Throwable $th) {
            return $this->sendError('Something went wrong. Please try it again!'. $th);
        }
    }

    public function cancelRequestTeam($team_id)
    {
        try {
            $checkExistTeamRequest = TeamMemberRequest::query()
                ->where('team_id', '=', $team_id)
                ->where('user_id', '=', auth()->user()->id)
                ->where('response', '=', null);
            if ($checkExistTeamRequest->exists()) {
                $checkExistTeamRequest->delete();
                return response()->json(['success' => true], 200);
            } else {
                return $this->sendResponse([], 'This request does not exist.');
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false], 404);
        }
    }

    public function userLeaveTeam($team_id)
    {
        try {
            $team = Team::find($team_id);
            if (empty($team)) {
                return $this->sendError('Team Not Found!');
            }
            $checkExistTeamMember = TeamMember::query()
                ->with('member_role')
                ->where('team_id', '=', $team_id)
                ->where('user_id', '=', auth()->user()->id)->first();
            if (!empty($checkExistTeamMember) ) {
                $listRoleTitle = $checkExistTeamMember->member_role->pluck('title')->toArray();
                if(in_array('Team Owner', $listRoleTitle)){
                    return $this->sendError('You must change the team owner before you leave!');
                }

                $format_create_time_now = date('Y-m-d H:i:s');
                $res = $checkExistTeamMember->update([
                    'deleted_at' => $format_create_time_now,
                ]);
                if ($res) {
                    // REMOVE REQUEST TEAM
                    TeamMemberRequest::where('team_id', $team_id)->where('user_id', auth()->user()->id)->delete();
                    return response()->json(['success' => true], 200);
                } else {
                    return response()->json(['success' => false], 404);
                }
            } else {
                return $this->sendResponse(
                    [],
                    'Your request is refused. Because you are not belong to this team.'
                );
            }
        } catch (\Throwable $th) {
            return $this->sendResponse(
                [],
                'Something went wrong. Please try it again!' . $th
            );
        }
    }
    public function getListTeamById(Request $request, $team_id)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $results = null;
        $results = TeamMember::Where('team_id', $team_id)
            ->with([
                'team_member:id,first_name,last_name,gender,birth_date,phone',
                'member_role:id,title',
            ])
            ->simplePaginate($limit);
        try {
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            TeamMemberResource::collection($results),
            'success'
        );
    }

    public function getAllRoleTeam(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $results = null;
        $results = Role::Where('type', 1)
            ->simplePaginate($limit)
            ->toArray();
        try {
        } catch (\Throwable $th) {
        }
        return $this->sendResponse($results['data'], 'success');
    }

    public function getRoleAndPermission($team_id)
    {
        $user_id = auth()->user()->id;

        $results = [];
        $dataCheckPermission = [];
        try {
            $results = TeamMember::Where('team_id', $team_id)
                ->where('user_id', $user_id)
                ->with(['member_role:id,title'])
                ->first();
            $help = new HelpController();
            $dataCheckPermission = $help->getPermissionRole($team_id);
        } catch (\Throwable $th) {
        }
        $response = [];
        if (!empty($results)) {
            $response = [
                'roles' => $results->member_role,
                'permission' => $dataCheckPermission,
            ];
        }

        try {
        } catch (\Throwable $th) {
        }
        return $this->sendResponse($response, 'success');
    }

    public function getDetailTeamMember($team_id, $member_id)
    {
        $user_id = auth()->user()->id;

        $results = [];
        $dataCheckPermission = [];
        try {
            $results = TeamMember::Where('id', $member_id)
                ->with([
                    'team_member:id,first_name,last_name,gender,birth_date,phone,phone_code,email,ec_fullname,ec_relationship,ec_main_pcode,ec_main_pnum,ec_alt_pnum,ec_email',
                    'member_role:id,title',
                ])
                ->first();
            $help = new HelpController();
            $dataCheckPermission = $help->getPermissionRole($team_id);
        } catch (\Throwable $th) {
        }

        try {
        } catch (\Throwable $th) {
        }
        return $this->sendResponse(
            new TeamMemberDetailResource($results),
            'success'
        );
    }

    public function removeTeamMember($team_id, $member_id)
    {
        $team = null;
        $teamMember = null;
        try {
            $team = Team::find($team_id);
            $teamMember = TeamMember::find($member_id);
        } catch (\Throwable $th) {
        }
        $user_id = auth()->user()->id;
        if (empty($team)) {
            return $this->sendError('Team Not Found!');
        }
        if (empty($teamMember)) {
            return $this->sendError('Team member Not Found!');
        }
        if ($user_id == $teamMember->user_id) {
            return $this->sendError('you cannot remove yourself');
        }

        // check permission
        $help = new HelpController();
        $dataCheckPermission = $help->getPermissionRole($team->id);
        if (!in_array('team_member_management', $dataCheckPermission)) {
            return $this->sendError('User does not have permission.');
        }

        // end check permission
        $format_create_time_now = date('Y-m-d H:i:s');

        $res = $teamMember->update(['deleted_at' => $format_create_time_now]);
        if ($res) {
             // REMOVE REQUEST TEAM
             TeamMemberRequest::where('team_id', $team_id)->where('user_id', $teamMember->user_id)->delete();
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 404);
        }
    }
    public function updateTeamMember(Request $request, $team_id, $member_id)
    {
        $validator = Validator::make($request->all(), [
            'roles' => 'sometimes|required',
            'jersey_number' => 'nullable|sometimes|numeric',
            'player_role' => 'nullable|sometimes',
            'status' => 'sometimes|required',
            'weight' => 'nullable|sometimes|numeric',
        ]);
        $input = $request->all();
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }

        $team = null;
        $teamMember = null;
        $roleTeam = null;
        try {
            $team = Team::find($team_id);
            $teamMember = TeamMember::find($member_id);
            if ($request->has('roles') && count($input['roles']) > 0) {
                if (
                    $input['roles'] !=
                    array_filter($input['roles'], 'is_numeric')
                ) {
                    return $this->sendError('Roles must is list number!');
                }
                $roleTeam = Role::where('type', 1)
                    ->where('title', 'Team Owner')
                    ->first();
                if (
                    !empty($roleTeam) &&
                    in_array($roleTeam->id, $input['roles'])
                ) {
                    return $this->sendError('cannot change role owner!');
                }
            }
        } catch (\Throwable $th) {
        }
        $user_id = auth()->user()->id;
        if (empty($team)) {
            return $this->sendError('Team Not Found!');
        }
        if (empty($teamMember)) {
            return $this->sendError('Team member Not Found!');
        }

        // check permission
        $help = new HelpController();
        $dataCheckPermission = $help->getPermissionRole($team->id);
        if (!in_array('team_member_management', $dataCheckPermission)) {
            return $this->sendError('User does not have permission.');
        }
        $teamMemberObj = new TeamMember();
        $dataUpdate = $teamMemberObj->fill($input)->toArray();
        $dataUpdate['updated_at'] = date('Y-m-d H:i:s');

        // end check permission
        $format_create_time_now = 
        $res = $teamMember->update($dataUpdate);
        if ($res) {
            // Update role member
            if ($request->has('roles') && count($input['roles']) > 0) {
                try {
                    // remove role Team Coach
                    $roleTeam_Coach = Role::where('type', 1)
                        ->where('title', 'Team Coach')
                        ->first();
                    if (
                        !empty($roleTeam_Coach) &&
                        in_array($roleTeam_Coach->id, $input['roles'])
                    ) {
                        $teamMemberRole = TeamMember::leftJoin(
                            'member_role',
                            'member_role.member_id',
                            'team_member.id'
                        )
                            ->where('team_id', $team_id)
                            ->where('member_role.role_id', $roleTeam_Coach->id)
                            ->get();
                        if (!empty($teamMemberRole)) {
                            foreach ($teamMemberRole as $item) {
                                MemberRole::where('member_id', $item->member_id)
                                    ->where('role_id', $item->role_id)
                                    ->delete();
                            }
                        }
                    }
                    // end
                    $lstRoleUpdate = array_unique(array_merge([$roleTeam->id], $input['roles']));
                    if (count($lstRoleUpdate) > 1) {
                        $teamMember->member_role()->sync($lstRoleUpdate);
                    }
                } catch (\Throwable $th) {
                    return $this->sendError('An error occurred.');
                }
            }
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 404);
        }
    }

    public function inviteUserToTeam(Request $request, $team_id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|sometimes|email|unique:users,email',
            'user_id' => 'nullable|sometimes|numeric',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }

        if (empty($input['user_id']) && empty($input['email'])) {
            return $this->sendError('Please select the inviter!');
        }

        $results = null;
        try {
            $team = Team::find($team_id);
            if (empty($team)) {
                return $this->sendError('Team not found!');
            }
            // check permission
            $help = new HelpController();
            $dataCheckPermission = $help->getPermissionRole($team->id);
            if (!in_array('team_member_management', $dataCheckPermission)) {
                return $this->sendError('User does not have permission.');
            }

            if($request->has('email') && !empty($input['email'])){
                Mail::to($input['email'])->send(new TeamInviteMember($team));
                return response()->json(['success' => true], 200);
            }else{
                $checkExistInvite = Invite::where('source_id', $team->id)
                ->where('source_type', 'team')
                ->where('target_id', $input['user_id'])
                ->where('target_type', 'user')
                ->first();
                if (!empty($checkExistInvite)) {
                    return $this->sendError('Sent an invitation to user.');
                } else {
                    $dataInvite = [
                        'creator_id' => auth()->user()->id,
                        'source_id' => $team->id,
                        'source_type' => 'team',
                        'target_id' => $input['user_id'],
                        'target_type' => 'user',
                        'email ' => isset($input['email']) ? $input['email'] : null,
                        'first_name ' => null,
                        'last_name ' => null,
                    ];

                    $results = Invite::create($dataInvite);
                }
            }
           
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred.' . $th);
        }
        return $this->sendResponse(new TeamResource($results), 'success');
    }

    public function getTeamRoster(Request $request, $team_id)
    {
        $input = $request->all();
        $results = null;
        try {
            $team = Team::find($team_id);
            if (empty($team)) {
                return $this->sendError('Team not found.');
            }

            $teamMember = TeamMember::Where('team_id', $team_id)
                ->with([
                    'team_member:id,first_name,last_name,gender,birth_date,phone,phone_code,email,ec_fullname,ec_relationship,ec_main_pcode,ec_main_pnum,ec_alt_pnum,ec_email',
                    'member_role:id,title',
                ])
                ->orderBy('updated_at', 'asc')
                ->get();
        } catch (\Throwable $th) {
        }

        if ($teamMember) {
            foreach ($teamMember as $item) {
                $roleTitle = [];
                if ($item->member_role) {
                    $roleTitle = $item->member_role->pluck('title')->toArray();
                }

                if (
                    $item->status == 'active' &&
                    in_array('Team Player', $roleTitle)
                ) {
                    $results['active'][] = new TeamMemberResource($item);
                }
                if (
                    $item->status == 'inactive' &&
                    in_array('Team Player', $roleTitle)
                ) {
                    $results['inactive'][] = new TeamMemberResource($item);
                }
                if (!in_array('Team Player', $roleTitle)) {
                    $results['none_player'][] = new TeamMemberResource($item);
                }
            }
        }
        return $this->sendResponse($results, 'success');
    }

    public function changeOwnerShip(Request $request, $team_id)
    {
        $user_id = auth()->user()->id;
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
        } catch (\Throwable $th) {
            if (empty($team)) {
                return $this->sendError('An error occurred.');
            }
        }

        $help = new HelpController();
        $dataCheckPermission = $help->getPermissionRole($team_id);
        if (!in_array('team_ownership_management', $dataCheckPermission)) {
            return $this->sendError('User does not have permission.');
        }
        $checkUserInTeam = [];
        try {
            // check auth in team
            $checkOwnerInTeam = TeamMember::where('team_id', $team_id)
                ->where('user_id', $user_id)
                ->first();
            if (empty($checkOwnerInTeam)) {
                return $this->sendError('Owner is not in group!');
            }
            // check user_id in team
            $checkUserInTeam = TeamMember::where('team_id', $team_id)
                ->where('user_id', $input['user_id'])
                ->first();
            if (empty($checkUserInTeam)) {
                return $this->sendError('User is not in group!');
            }
            $role_Owner = Role::where('title', 'Team Owner')->first();
            if (!empty($role_Owner)) {
                $successDelete = MemberRole::where('role_id', $role_Owner->id)
                    ->where('member_id', $checkOwnerInTeam->id)
                    ->delete();
                if ($successDelete) {
                    if (
                        !$checkUserInTeam->member_role->contains(
                            $role_Owner->id
                        )
                    ) {
                        $checkUserInTeam
                            ->member_role()
                            ->attach([$role_Owner->id]);
                    }
                }
            }
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred.');
        }

        return response()->json(['success' => true], 200);
    }

    public function getMyTeam(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = $request->user();
        $input = $request->all();
        $results = null;
        try {
            $teamMember = $user
                ->memberTeams()
                ->with('teamMember', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                    $q->with('member_role', function ($q) {});
                })
                ->whereHas('teamMember', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                    $q->whereHas('member_role');
                })
                ->withCount('members')
                ->get()
                ->toArray();
            if (!empty($teamMember)) {
                foreach ($teamMember as $item) {
                    if (!is_null($item['team_member'])) {
                        $listTitle = Arr::pluck(
                            $item['team_member'][0]['member_role'],
                            'title'
                        );
                        if (in_array('Team Owner', $listTitle)) {
                            $results['owned'][] = new MyTeamResource(
                                json_decode(json_encode($item))
                            );
                        } else {
                            $results['membership'][] = new MyTeamResource(
                                json_decode(json_encode($item))
                            );
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
        }

        return $this->sendResponse($results, 'success');
    }

    public function getTeamOwned(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = $request->user();
        $results = [];
        try {
            $results = $user
                ->memberTeams()
                ->with('teamMember.member_role.permissions')
                ->whereHas('teamMember', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                    $q->whereHas('member_role', function ($q) {
                        $q->whereHas('permissions', function ($q) {
                            $q->where('title', 'team_settings_management');
                        });
                    });
                })
                ->withCount('members')
                ->get();
        } catch (\Throwable $th) {
        }

        return $this->sendResponse(
            MyTeamResource::collection($results),
            'success'
        );
    }

    public function getTeamManager(Request $request)
    {
        $user_id = auth()->user()->id;
        $user = auth()->user();
        $input = $request->all();
        $results = [];

        try {
            $results = $user
                ->memberTeams()
                ->whereHas('teamMember', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                    $q->whereHas('member_role', function ($q) {
                        $q->where('title', 'Team Owner');
                        $q->orWhere('title', 'Team Manager');
                    });
                })
                ->withCount('members')
                ->get();
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred.');
        }
        return $this->sendResponse(
            MyTeamResource::collection($results),
            'success'
        );
    }

    public function teamRequestAccess(
        Request $request,
        $team_id,
        $team_request_id
    ) {
        $user = auth()->user();
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'response' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        $results = null;
        try {
            $team = Team::find($team_id);
            if (empty($team)) {
                return $this->sendError('Team not found!');
            }

            $team_request = TeamMemberRequest::find($team_request_id);
            if (empty($team_request)) {
                return $this->sendError('Request not found!');
            }
            if (!is_null($team_request->response)) {
                return $this->sendError('The request already been responsed!');
            }
            // check permission
            $help = new HelpController();
            $dataCheckPermission = $help->getPermissionRole($team->id);
            if (!in_array('team_member_management', $dataCheckPermission)) {
                return $this->sendError('User does not have permission.');
            }

            // UPDATE  TEAM REQUEST
            $team_request->update(['response' => $input['response']]);

            if($input['response']){
                // CHECK EXIST TEAM
                $teamMemberExist = TeamMember::where(
                    'user_id',
                    $team_request->user_id
                )
                    ->where('team_id', $team->id)
                    ->withTrashed()
                    ->first();
                $role_Player = Role::where('title', 'Team Player')->first();
                if (!empty($teamMemberExist)) {
                    $teamMemberExist->update(['deleted_at' => null]);
                    $teamMemberExist->member_role()->sync([$role_Player->id]);
                    $results = $teamMemberExist;
                } else {
                    $teamMemberCreated = TeamMember::create([
                        'user_id' => $team_request->user_id,
                        'team_id' => $team_request->team_id,
                        'status' => 'active',
                        'jersey_number' => null,
                        'player_role' => null,
                        'weight' => null,
                    ]);
                    if (!empty($teamMemberCreated)) {
                        $teamMemberCreated->member_role()->sync([$role_Player->id]);
                    }
                    $results = $teamMemberCreated;
                }
                return $this->sendResponse($results, 'success');
            }else{
                return $this->sendResponse(null, 'reject success');
            }
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred.');
        }

    }

    public function updateTeamRoster(Request $request, $team_id){
        $input = $request->all();
        $user = $request->user();
        $team = null;

        $validator = Validator::make($request->all(), [
            'team_roster' => 'required|array',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        try {
            $team = Team::find($team_id);
            if(empty($team)){
                return $this->sendError('Team not found.');
            }
        } catch (\Throwable $th) {
            return $this->sendError('Update failed.' . $th);
        }

        $help = new HelpController();
        $dataCheckPermission = $help->getPermissionRole($team_id);
        if (!in_array('team_member_management', $dataCheckPermission)) {
            return $this->sendError('User does not have permission.');
        }

        try {
            $listTeamMember = isset($input['team_roster']) ? $input['team_roster'] : [];
            if(count($listTeamMember)){
                foreach ($listTeamMember as $value) {
                    if (empty($value['id'])) { 
                        continue;
                    }
                    $teamMember = TeamMember::where('id', $value['id'])->where('team_id', $team_id)->first();
                    if(!empty($teamMember) && !empty($value['weight']) && $teamMember->weight != $value['weight']){
                        $teamMember->update([
                            'weight' => $value['weight']
                        ]);
                    }
                }
            }
        } catch (\Throwable $th) {
            return $this->sendError('Update failed.');
        }
        return response()->json(['success' => true], 200);
    }

    public function teamVerify(Request $request){
        $result = false;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'not_id' => 'nullable|sometimes|numeric',
        ]);
        $name = $request->input('name');
        $not_id = $request->has('not_id') ? $request->input('not_id') : null;
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }

        try {
            $existTeam = Team::where(function($q) use($name, $not_id){
                $q->whereRaw("BINARY `name`= ?",[$name]);
                if(!is_null($not_id)){
                    $q->where('id', '!=', $not_id);
                }
            })->first();
            if(!empty($existTeam)){
                $result = true;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return response()->json(['success' => $result], 200);
    }
}
