<?php

namespace App\Http\Livewire\Team\Member;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Mail\TeamInviteMember;
use App\Models\Invite;
use App\Models\MemberRole;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamBlock;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use stdClass;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;
    public $invite;
    public $showInvite = false;
    public array $listsForFields = [];

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    protected $messages = [
        'invite.email.required' => 'Enter Email',
        'invite.email.email' => 'Enter Email is invalid',
    ];

    public function toggleShowInvite()
    {
        $this->showInvite = !$this->showInvite;
    }

    public function invite()
    {
        $this->validate();
        $user = User::find($this->invite['email'])?->first();
        
        $dataInvite = [
            'source_id' => $this->team->id,
            'source_type' => 'team',
            'target_id' => $user? $user->id: null,
            'target_type' => 'user',
            'email' => !$user? $this->invite['email']: null,
            'first_name ' => $user? $user->first_name: null,
            'last_name ' => $user? $user->last_name: null,
        ];

        Invite::create($dataInvite);
        if ($this->invite['email']) {
            Mail::to($this->invite['email'])->send(new TeamInviteMember($this->team));
        }
        $this->showInvite = false;
    }

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount(Team $team)
    {
        $this->team = $team;
    }

    public function updated($propertyName)
    {
        $this->validateOnly('invite.email');
        if ($this->invite && !is_numeric($this->invite['email'])) {
            $this->addError('invite.email', 'This user does not exist in PALARO. Click invite to send an invitation to register on PALARO');

        }
    }
    protected function rules(): array
    {
        if ($this->invite && is_numeric($this->invite['email'])) {
            return [
                'invite.email' => [
                    'required',
                    'integer'
                ],
            ];
        }
        return [
            'invite.email' => [
                'required',
                'email'
            ],
        ];
    }
    public function render()
    {
        $this->team_members = $this->team->teamMember()->get();
        $this->listsForFields['users'] = User::select(['id', 'first_name', 'last_name', 'email'])->get()->mapWithKeys(function ($user) {
            return [
                $user->id => $user->name . ($user->email? "($user->email)": "") 
            ];
        })->toArray();
        return view('livewire.team.member.index');
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('team_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Team::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(TeamMember $teamMember)
    {
        abort_if(Gate::denies('team_member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (!$teamMember->isOwner) {
            $teamMember->delete();
        }
    }
    public function setOwner(TeamMember $teamMember)
    {
        abort_if(Gate::denies('team_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roleTeam = Role::where('type', 1)
            ->where('title', 'Team Owner')
            ->first();
        if ($roleTeam) {
            $memberIds = TeamMember::where('team_id', $teamMember->team_id)->pluck('id')->toArray();
            MemberRole::whereIn('member_id', $memberIds)->where('role_id', $roleTeam->id)->delete();
            $teamMember->member_role()->attach($roleTeam->id);
        }
    }

    public function block(TeamMember $teamMember)
    {
        abort_if(Gate::denies('team_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if (!$teamMember->isOwner) {
            $teamBlock = new TeamBlock();
            $teamBlock->team_id = $this->team->id;
            $teamBlock->user_id = $teamMember->user_id;
            $teamBlock->save();
            $teamMember->delete();
        }
    }
}
