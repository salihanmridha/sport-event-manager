<?php

namespace App\Http\Livewire\Event;

use App\Models\Event;
use App\Models\Sport;
use App\Models\EventPosition;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Edit extends Component
{
    public Event $event;

    public $showChargeFee = false;

    public $show_sport = false;

    public $show_title = false;

    public $show_mechanics = false;

    public $show_max_player_per_team = false;

    public $show_application_type = false;

    public $show_field_event = false;

    public $showApplicationTeam = false;

    public $show_is_set_role = false;

    public $showApplicationType = false;

    public $max_number_join = false;

    public $show_is_set_role_pickup = false;

    public $show_role = false;

    public $show_field_pickup = false;

    public $show_field_max_player_per_team = false;

    public $show_field_session = false;

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
        $this->initListsForFields();

        $this->mediaCollections = [
            'upload_photo' => $event->upload_photo,
        ];

        if ($this->event->is_paid  === false) {
            $this->showChargeFee = true;
        }

        if ($this->event->is_set_role == false) {
            $this->showApplicationType = true;
        }

        if ($this->event->event_type == 'pickup') {
            $this->show_sport = true;
            $this->show_field_pickup = true;
            $this->show_field_max_player_per_team = true;
            $this->show_is_set_role_pickup = true;
            $this->show_max_player_per_team = true;
            $this->show_role = true;
        }

        if ($this->event->event_type == 'session') {
            $this->show_field_session = true;
            $this->show_title = true;
            $this->show_is_set_role = true;
            $this->max_number_join = true;
        }

        if ($this->event->event_type == 'sport') {
            $this->show_field_event = true;
            $this->show_title = true;
            $this->show_is_set_role = true;
            $this->show_mechanics = true;
            $this->max_number_join = true;
        }

        if ($this->event->application_type == 'individual') {
            $this->show_application_type = true;
        }

        if ($this->event->application_type == 'team') {
            $this->showApplicationTeam = true;
        }
    }

    public function render()
    {
        return view('livewire.event.edit');
    }

    public function submit()
    {
        $isUsersJoinedEmpty = $this->event->usersJoined()->get()->isEmpty();
        $isTeamsJoinedEmpty = $this->event->teamsJoined()->get()->isEmpty();
        if ($isUsersJoinedEmpty && $isTeamsJoinedEmpty) {
            $this->validate();

            $this->event->save();

            $this->syncMedia();

            if ($this->event->event_type == 'session') {

                return redirect()->route('admin.events.index', ['eventType' => 'session']);
            } elseif ($this->event->event_type == 'sport') {

                return redirect()->route('admin.events.index', ['eventType' => 'sport']);
            } else {

                return redirect()->route('admin.events.index', ['eventType' => 'league']);
            }
        } else {
            session()->flash('message', 'Event can not edited');
        }
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->event->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
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
            'mediaCollections.upload_photo' => [
                'array',
                'nullable',
            ],
            'mediaCollections.upload_photo.*.id' => [
                'integer',
                'exists:media,id',
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
            'event.fee' => [
                'nullable',
            ],
            'event.event_ownership' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['event_ownership'])),
            ],
            'event.title' => [
                'nullable',
                'string'
            ],
            'event.mechanics' => [
                'nullable',
                'string'
            ],
            'event.creator_id' => [
                'integer',
                'exists:users,id',
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
        $this->listsForFields['event_ownership'] = $this->event::OWNERSHIP_TYPE_SELECT;
        $this->listsForFields['creator'] = User::pluck('email', 'id')->toArray();

    }
}
