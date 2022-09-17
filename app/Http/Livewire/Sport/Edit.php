<?php

namespace App\Http\Livewire\Sport;

use App\Models\Sport;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
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
        $this->sport = $sport;
        $this->initListsForFields();

        $this->mediaCollections = [
          'sport' => $sport->getIconLiveWire(),
        ];
    }

    public function getMediaCollections($name)
    {
        return $this->mediaCollections[$name];
    }

    public function render()
    {
        return view('livewire.sport.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->sport->save();
        $this->syncMedia();

        return redirect()->route('admin.sports.index');
    }

    public function syncMedia(): void
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
                'nullable',
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
            // 'mediaCollections.sport' => [
            //   'array',
            //   'nullable',
            // ],
            'mediaCollections.sport.*.id' => [
                'integer',
                'exists:media,id',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        //$this->listsForFields['creator']                = User::pluck('name', 'id')->toArray();
        $this->listsForFields['creator']                = User::pluck('email','id')->toArray();
        $this->listsForFields['is_require_choose_role'] = $this->sport::IS_REQUIRE_CHOOSE_ROLE_RADIO;
    }
}
