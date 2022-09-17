<?php

namespace App\Http\Livewire\Team;

use App\Enums\GenderEnum;
use App\Models\OrganizationRole;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Models\Sport;
use App\Models\TeamLevel;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{
    public Team $team;

    public array $listsForFields = [];

    public array $mediaToRemove = [];

    public array $mediaCollections = [];

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect($this->mediaCollections[$media['collection_name']]);
        $this->mediaCollections[$media['collection_name']] = $collection->reject(fn ($item) => $item['uuid'] === $media['uuid'])->toArray();
        $this->mediaToRemove[] = $media['uuid'];
    }

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->initListsForFields();
    }

    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
    // }

    public function render()
    {
        return view('livewire.team.create');
    }

    public function submit()
    {
        $this->validate();
        $AgeGroup = $this->team->age_group;
        $AgeGroups = [
            0 => [
                'from' => 0,
                'to' => 99,
            ],
            1 => [
                'from' => 0,
                'to' => 2,
            ],
            2 => [
                'from' => 3,
                'to' => 12
            ],
            3 => [
                'from' => 13,
                'to' => 19
            ],
            4 => [
                'from' => 20,
                'to' => 30
            ],
            5 => [
                'from' => 31,
                'to' => 45
            ],
            6 => [
                'from' => 46,
                'to' => 99
            ],
        ];

        if (isset($AgeGroups[$AgeGroup])) {
            $from = $AgeGroups[$AgeGroup]['from'];
            $to = $AgeGroups[$AgeGroup]['to'];
            $this->team->start_age = $from;
            $this->team->end_age = $to;
        }
        $this->team->save();

        $teamMember = $this->team->creator_id;

        $teamMembers = $this->team->teamMember()->create([
            'user_id' => $teamMember,
        ]);

        $query = Role::where('type', 1)->first();

        $teamMembers->member_role()->sync([
            'role_id' => $query->id,
        ]);
        $this->syncMedia();

        return redirect()->route('admin.teams.index');
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->team->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [
            'team.name' => [
                'string',
                'nullable',
                'required',
                'max:50'
            ],
            'team.sport_id' => [
                'integer',
                'exists:sports,id',
                'nullable',
                'required'
            ],
            'team.creator_id' => [
                'integer',
                'exists:users,id',
                'required',
                'nullable',
            ],
            'team.org_role_id' => [
                'integer',
                'exists:organization_role,id',
                'nullable',
            ],
            'team.gender' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['gender'])),
            ],
            'team.level_id' => [
                'integer',
                'exists:team_level,id',
                'nullable',
                'required'
            ],
            'team.age_group' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['age_group'])),
                'required'
            ],
            'team.oganization_name' => [
                'string',
                'nullable',
                'max:50'
            ],
            'team.oganization_url' => [
                'string',
                'nullable',
            ],
            'team.division' => [
                'string',
                'nullable',
                'max:50'
            ],
            'team.season' => [
                'string',
                'nullable',
                'max:50'
            ],
            'mediaCollections.team_avatar_image' => [
                'array',
                'nullable',
            ],
            'mediaCollections.team_avatar_image.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'mediaCollections.team_background' => [
                'array',
                'nullable',
            ],
            'mediaCollections.team_background.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'team.bio' => [
                'string',
                'nullable',
                'max:300'
            ],
        ];
    }

    protected $messages = [
        'team.name.required' => 'Enter Team Name',
        'team.sport_id.required' => 'Enter Sport',
        'team.creator_id.required' => 'Enter Team Owner',
        'team.level_id.required' => 'Enter Level',
        'team.age_group.required' => 'Enter Age Group',
        'team.gender.required' => 'Enter Gender',
        'team.bio.max' => 'The maximum character length is 300 characters',
        'team.season.max' => 'The maximum character length is 50 characters',
        'team.oganization_name.max' => 'The maximum character length is 50 characters',
        'team.division.max' => 'The maximum character length is 50 characters',
        'team.name.max' => 'The maximum character length is 50 characters',
    ];

    protected function initListsForFields(): void
    {
        $this->listsForFields['sport']   = Sport::pluck('name', 'id')->toArray();
        $this->listsForFields['creator'] = User::pluck('email', 'id')->toArray();
        $this->listsForFields['team_level']   = TeamLevel::pluck('name', 'id')->toArray();
        $this->listsForFields['organization_role']   = OrganizationRole::pluck('name', 'id')->toArray();
        $enum = GenderEnum::toArray();
        unset($enum['all']);
        $this->listsForFields['gender'] = $enum;
        $this->listsForFields['age_group']   = $this->team::AGE_SELECT;
    }
}
