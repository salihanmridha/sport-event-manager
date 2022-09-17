<?php

namespace App\Http\Livewire\Team;

use App\Models\OrganizationRole;
use App\Models\Sport;
use App\Models\Team;
use App\Models\TeamLevel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
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
        $this->mediaCollections = [
            'team_avatar_image' => $team->getTeamAvatarLiveWire()
            ,
            'team_background' => $team->getTeamBackgroundLiveWire()
        ];
    }

    public function render()
    {
        return view('livewire.team.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->team->save();
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
            ],
            'team.sport_id' => [
                'integer',
                'exists:sports,id',
                'nullable',
            ],
            'team.creator_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'team.gender' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['gender'])),
            ],
            'team.level_id' => [
                'integer',
                'exists:team_level,id',
                'nullable',
            ],
            'team.oganization_name' => [
                'string',
                'nullable',
            ],
            'team.oganization_url' => [
                'string',
                'nullable',
            ],
            'team.division' => [
                'string',
                'nullable',
            ],
            'team.season' => [
                'string',
                'nullable',
            ],
            'team.bio' => [
                'string',
                'nullable',
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
            'team.org_role_id' => [
                'integer',
                'exists:organization_role,id',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['sport']   = Sport::pluck('name', 'id')->toArray();
        $this->listsForFields['team_level']   = TeamLevel::pluck('name', 'id')->toArray();
        $this->listsForFields['organization_role']   = OrganizationRole::pluck('name', 'id')->toArray();
        $this->listsForFields['creator'] = User::pluck('email', 'id')->toArray();
        $this->listsForFields['gender'] = $this->team::GENDER_SELECT;
        $this->listsForFields['age_group']   = $this->team::AGE_SELECT;
    }
}
