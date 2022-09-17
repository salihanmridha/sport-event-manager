<?php

namespace App\Http\Livewire\Team;

use App\Http\Livewire\WithConfirmation;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\TeamMemberRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class MemberRequest extends Component
{
    use WithConfirmation;

    public $teamId;

    public function mount($teamId)
    {
        $this->teamId = $teamId;
    }

    public function render()
    {
        return view('livewire.team.member-request', [
            'members' => TeamMemberRequest::query()
                ->where('team_id', $this->teamId)
                ->whereNull('response')
                ->with(['user' => function ($q) {
                    $q->select('id', 'first_name', 'last_name', 'email')
                        ->withCount('memberTeams')
                        ->with('sports:id,name');
                }])->paginate(20),
        ]);
    }

    public function updateResponse(TeamMemberRequest $teamMemberRequest, $responseValue)
    {
        abort_if(Gate::denies('team_edit') || $teamMemberRequest->response != null ,
            Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teamMemberRequest->response = $responseValue;
        $teamMemberRequest->save();

        // Access member
        if($responseValue){
            // Get role of Player
            $roleMember = Role::query()->whereTitle('Team Player')->first();

            // Check if a member exists in this team
            $memberJoinedTeam = TeamMember::query()->withTrashed()
                ->where('team_id', $teamMemberRequest->team_id)
                ->where('user_id', $teamMemberRequest->user_id)
                ->first();
            if($memberJoinedTeam){
                // Delete all old role of this member
                $memberJoinedTeam->member_role()->delete();
            }

            // Add this member to team
            $newMember = TeamMember::query()->withTrashed()->updateOrCreate([
                'user_id' => $teamMemberRequest->user_id,
                'team_id' => $teamMemberRequest->team_id
            ],
            [
                'deleted_at' => null
            ]);

            // Add new role for this member
            $newMember->member_role()->sync([$roleMember->id ?? 4]); //TODO: remove default value
        }
    }
}

