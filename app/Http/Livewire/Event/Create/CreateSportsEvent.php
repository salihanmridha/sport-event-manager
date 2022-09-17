<?php

namespace App\Http\Livewire\Event\Create;

use App\Models\Event;
use App\Models\Sport;
use App\Models\EventPosition;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CreateSportsEvent extends Component
{
    public Event $event;

    public $showChargeFee = NULL;

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

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->event->is_set_role = false;
        $this->event->is_paid = false;
        $this->initListsForFields();
    }

    // public function generateCode(): void
    // {
    //     $uppercase = range('A', 'Z');
    // }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->event->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function render()
    {
        return view('livewire.event.create.create-sports-event');
    }

    public function submit()
    {
        $this->validate();
        $this->event->save();
        $this->syncMedia();

        return redirect()->route('admin.events.index');
    }

    protected function rules(): array
    {
        return [
            'event.event_type' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['event_type'])),
            ],
            'event.sport_id' => [
                'integer',
                'exists:sports,id',
                'nullable',
            ],
            'event.max_team' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'event.max_player_per_team' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'event.application_type' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['application_type'])),
            ],
            'event.description' => [
                'string',
                'nullable',
            ],
            'event.start_date_time' => [
                'nullable',
                'date_format:' . config('project.datetime_format'),
            ],
            'event.end_date_time' => [
                'nullable',
                'date_format:' . config('project.datetime_format'),
            ],
            'event.gender' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['gender'])),
            ],
            'event.is_public' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['is_public'])),
            ],
            'event.age_group' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['age_group'])),
            ],
            'event.is_public' => [
                'boolean'
            ],
            'event.is_set_role' => [
                'boolean'
            ],
            'event.is_paid' => [
                'boolean'
            ],
            'event.caption' => [
                'string',
                'nullable',
            ],
            'event.about' => [
                'string',
                'nullable',
            ],
            'event.location' => [
                'string',
                'nullable',
            ],
            'event.lat' => [
                'required',
                'nullable',
            ],

            'event.long' => [
                'required',
                'nullable',
            ],
            'event.title' => [
                'nullable',
            ],
            'event.is_unlimit_max' => [
                'boolean',
            ],
            
            'event.mechanics' => [
                'required',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['event_type']       = $this->event::EVENT_TYPE_SELECT;
        $this->listsForFields['sport']            = Sport::pluck('name', 'id')->toArray();
        $this->listsForFields['application_type'] = $this->event::APPLICATION_TYPE_SELECT;
        $this->listsForFields['gender'] = $this->event::GENDER_SELECT;
        $this->listsForFields['is_public'] = $this->event::PRIVACY_SELECT;
        $this->listsForFields['age_group'] = $this->event::AGE_SELECT;
    }
}
