<?php

namespace App\Http\Livewire\Sport;

use App\Models\Sport;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{
    public Sport $sport;
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

    public function mount(Sport $sport)
    {
        $this->sport                         = $sport;
        $this->sport->is_require_choose_role = '0';
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.sport.create');
    }

    public function submit()
    {
        $this->validate();

        $this->sport->save();
        $this->syncMedia();

        return redirect()->route('admin.sports.index');
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->sport->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [
            'sport.code' => [
                'string',
                'min:0',
                'max:50',
                'required',
            ],
            'sport.name' => [
                'string',
                'min:0',
                'max:255',
                'required',
            ],
            'sport.description' => [
                'string',
                'nullable',
            ],
            'sport.creator_id' => [
                'integer',
                'exists:users,id',
                'required',
            ],
            'sport.max_player_per_team' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'sport.min_player_per_team' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'sport.is_require_choose_role' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['is_require_choose_role'])),
            ],
            'mediaCollections.icon.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'mediaCollections.icon' => [
                'array',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['creator'] = User::select(['id', 'first_name', 'last_name'])->get()->mapWithKeys(function ($user) {
            return [
                $user->id => $user->name
            ];
        })->toArray();
        $this->listsForFields['is_require_choose_role'] = $this->sport::IS_REQUIRE_CHOOSE_ROLE_RADIO;
    }
}
