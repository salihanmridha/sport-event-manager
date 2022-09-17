<?php

namespace App\Http\Livewire\Team\Member;

use App\Enums\TeamMemberStatusEnum;
use App\Models\OrganizationRole;
use App\Models\Role;
use App\Models\Sport;
use App\Models\Team;
use App\Models\TeamLevel;
use App\Models\TeamMember;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Team $team;
    public TeamMember $member;

    public array $listsForFields = [];
    public array $mediaToRemove = [];

    public array $mediaCollections = [];
    public array $rolesSelected = [];


    public function mount(Team $team, TeamMember $member)
    {
        $rolesSelected = $member->member_role()->pluck('id')->toArray();
        $this->initListsForFields();
        $this->team = $team;
        $this->member = $member;
        $this->roles = Role::Where('type', 1)->get();
        $this->rolesSelected = $rolesSelected;
    }

    public function render()
    {
        return view('livewire.team.member.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->member->save();
        $this->member->member_role()->sync($this->rolesSelected);

        return redirect()->route('admin.teams.show', [
            'team'=> $this->team
        ]);
    }

    protected function rules(): array
    {
        return [
            'member.jersey_number' => [
                'integer',
                'nullable',
            ],
            'member.status' => [
                'string',
                'nullable',
            ],
            'member.player_role' => [
                'string',
                'nullable',
            ],
            'rolesSelected'=> [
                'array'
            ]
        ];
    }
    protected function initListsForFields(): void
    {
        $this->listsForFields['status']   = TeamMemberStatusEnum::labels();
    }
}
