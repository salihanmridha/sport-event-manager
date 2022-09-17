<?php

namespace App\Http\Livewire\Event;

use App\Enums\GenderEnum;
use App\Models\Event;
use App\Models\Sport;
use Livewire\Component;
use App\Models\EventPosition;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{
    public Event $event;
    public $show_sport = false;

    public $show_title = false;

    public $show_mechanics = false;

    public $show_field_max_player_per_team = false;

    public $show_max_team = false;

    public $show_field_event = false;

    public $show_is_set_role = false;

    public $max_number_join = false;

    public $show_is_set_role_pickup = false;

    public $show_role = false;

    public $show_field_pickup = false;

    public $show_field_session = false;

    public $showApplicationType = NULL;

    public $showPlayerRole = NULL;

    public $showChargeFee = NULL;

    public $showJoinTeam = NULL;

    public $showEventOwnership = NULL;

    public array $listsForFields = [];

    public array $event_position = [];

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
        $event_type = isset($_GET['eventType']) ? $_GET['eventType'] : NULL;
        if (in_array($event_type, array_keys($this->listsForFields['event_type']))) {
            $this->event->event_type = $event_type;
        } else {
            $this->event->event_type = "sport";
        }

        if ($this->event->event_type == 'pickup') {
            $this->show_sport = true;
            $this->show_field_pickup = true;
            $this->show_is_set_role_pickup = true;
            $this->show_max_team = true;
            $this->show_field_max_player_per_team = true;
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
        if ($this->event->event_type == 'league') {
        }
    }

    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
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
        return view('livewire.event.create');
    }

    public function submit()
    {

        $this->validate();

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < 8; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $this->event->private_code = $randomString;
        $ageGroup = $this->event->age_group;
        $ageGroups = [
            0 => [
                'from' => 0,
                'to' => 99
            ],
            1 => [
                'from' => 0,
                'to' => 2
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
        if (isset($ageGroups[$ageGroup])) {
            $from = $ageGroups[$ageGroup]['from'];
            $to = $ageGroups[$ageGroup]['to'];
            $this->event->start_age = $from;
            $this->event->end_age = $to;
        }
        if($this->event->event_ownership ==='palaro'){
        $this->event->creator_id = auth()->user()->id;
        }
        $this->event->save();
        $eventPosition = $this->event_position;
        $maxTeam = $this->event->max_team;
        $i = 1;
        $eventPosition = array_map(function ($item) use (&$i) {
            return [
                'name' => $item['name'],
                'weight' => $i++
            ];
        }, $eventPosition);

        $eventSquad = [];
        for ($i = 1; $i <= $maxTeam; $i++) {
            array_push($eventSquad, [
                'name' => "Team $i",
            ]);
        }
        $this->event->event_position()->createMany($eventPosition);
        $this->event->event_squad()->createMany($eventSquad);
        $this->syncMedia();

        if ($this->event->event_type == 'session') {

            return redirect()->route('admin.events.index', ['eventType' => 'session']);
        } elseif ($this->event->event_type == 'sport') {

            return redirect()->route('admin.events.index', ['eventType' => 'sport']);
        } else {

            return redirect()->route('admin.events.index', ['eventType' => 'league']);
        }
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
                'min:2',
                'max:4',
                'nullable',
            ],
            'event.max_player_per_team' => [
                'integer',
                'min:0',
                'max:10',
                'nullable',
                'required_if:event.is_set_role,false'
            ],
            'event.application_type' => [
                'in:' . implode(',', array_keys($this->listsForFields['application_type'])),
                'required',
                'nullable',
            ],
            'event.start_date_time' => [
                'nullable',
                'date_format:' . config('project.datetime_format'),
                'before_or_equal:event.end_date_time',
                'required',
            ],
            'event.end_date_time' => [
                'nullable',
                'date_format:' . config('project.datetime_format'),
                'after_or_equal:event.start_date_time',
                'required',
            ],
            'event.gender' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['gender'])),
            ],
            'event.is_public' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['is_public'])),
                'boolean'
            ],
            'event.age_group' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['age_group'])),
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
                'max:300'
            ],
            'event.about' => [
                'string',
                'nullable',
                'max:300'
            ],
            'event.location' => [
                'string',
                'required',
                'nullable'
            ],
            'event.lat' => [
                'required',
                'numeric'
            ],

            'event.long' => [
                'required',
                'numeric'
            ],
            'event.fee' => [
                'nullable',
                'gt:0',
                'required_if:event.is_paid,false'
            ],
            'event.private_code' => [
                'nullable',
            ],
            'event.event_ownership' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['event_ownership'])),
                'nullable',
            ],
            'event.title' => [
                'required',
                'string',
                'nullable',
            ],
            'event.mechanics' => [
                'nullable',
                'string'
            ],
            'event.creator_id' => [
                'integer',
                'exists:users,id',
                'nullable',
                'required_if:event.event_ownership,user'
            ],
        ];
    }

    protected $messages = [
        'event.lat.required' => 'Enter Latitude',
        'event.title.required' => 'Enter Title',
        'event.long.required' => 'Enter Longitude',
        'event.location.required' => 'Enter Location',
        'event.start_date_time.required' => 'Enter Start Date Time',
        'event.end_date_time.required' => 'Enter End Date Time',
        'event.age_group.required' => 'Enter Age Group',
        'event.gender.required' => 'Enter Gender',
        'event.event_ownership.required' => 'Enter Event Ownership',
        'event.is_public.required' => 'Enter Game Privacy',
        'event.caption.max' => 'The maximum character length is 300 characters',
        'event.about.max' => 'The maximum character length is 300 characters',
        'event.start_date_time.before_or_equal' => 'Event end date and time must greater that its start date and time',
        'event.end_date_time.after_or_equal' => 'Event end date and time must greater that its start date and time',
        'event.fee.gt' => 'Input value must be greater than 0',
        'event.application_type.required' => 'Enter Application Type',
        'event.fee.required_if' => 'Enter Fee',
        'event.max_player_per_team.required_if' => 'Enter Max number of joiners',
        'event.creator_id.required_if' => 'Enter Event Ownership',
    ];


    protected function initListsForFields(): void
    {
        $this->listsForFields['event_type']       = $this->event::EVENT_TYPE_SELECT;
        $this->listsForFields['sport']            = Sport::pluck('name', 'id')->toArray();
        $this->listsForFields['application_type'] = $this->event::APPLICATION_TYPE_SELECT;
        $this->listsForFields['gender'] = GenderEnum::toArray();
        $this->listsForFields['is_public'] = $this->event::PRIVACY_SELECT;
        $this->listsForFields['age_group'] = $this->event::AGE_SELECT;
        $this->listsForFields['event_ownership'] = $this->event::OWNERSHIP_TYPE_SELECT;
        $this->listsForFields['creator'] = User::pluck('email', 'id')->toArray();
    }
}
