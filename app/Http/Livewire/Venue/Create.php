<?php

namespace App\Http\Livewire\Venue;

use App\Models\Country;
use App\Models\Role;
use App\Models\Sport;
use App\Models\User;
use App\Models\Venue;
use App\Models\VenueType;
use App\Models\WeekDay;
use App\Rules\CompareTwoTimeRule;
use App\Rules\PhoneUniqueRule;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{

    public Venue $venue;

    public array $mediaToRemove = [];

    public array $mediaCollections = [];

    public array $listsForFields = [];

    public array $user = [];

    public string $type = '';

    public array $workdays = [];

    public function mount(Venue $venue)
    {
        $this->venue = $venue;

        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.venue.create');
    }

    public function submit()
    {
        $items = $this->validate();
        $owner = User::query()->create($items['user']);
        $roleVenue = Role::query()->whereTitle('Venue Manager')->firstOrFail();
        $owner->roles()->sync([$roleVenue->id]);
        //TODO: Sending mail


        $this->venue->owner_id = $owner->id;
        $this->venue->type = $items['type'];
        $this->venue->creator_id = auth()->id();
        $this->venue->save($items['venue']);
        $this->venue->weekdays()->sync($items['workdays']);
        $this->syncMedia();

        return redirect()->route('admin.venues.index');
    }

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect($this->mediaCollections[$media['collection_name']]);
        $this->mediaCollections[$media['collection_name']] = $collection->reject(fn($item) => $item['uuid'] === $media['uuid'])->toArray();
        $this->mediaToRemove[] = $media['uuid'];
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->venue->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();
        $this->listsForFields['phone_code'] = Country::pluck('phone_code', 'id')->toArray();
        $this->listsForFields['workdays'] = WeekDay::pluck('name', 'code')->toArray();
        $this->listsForFields['venueType'] = VenueType::pluck('name', 'id')->toArray();
    }

    protected array $validationAttributes = [
        'user.email' => 'email',
        'user.first_name' => 'first name',
        'user.last_name' => 'last name',
        'user.phone' => 'phone',
        'user.phone_code' => 'phone code'
    ];

    protected function rules(): array
    {
        return [
            'workdays' => [
                'required',
                'array',
            ],
            'workdays.*' => [
                'required',
                'exists:weekdays,code',
            ],
            'venue.name' => [
                'required',
                'string',
            ],
            'venue.address' => [
                'required',
                'string',
            ],
            'venue.lat' => [
                'required',
            ],
            'venue.long' => [
                'required',
            ],
            'venue.country_id' => [
                'required',
                'exists:countries,id'
            ],
            'venue.phone_code' => [
                'nullable',
                'int',
            ],
            'venue.phone_number' => [
                'sometimes',
                'nullable',
                'string',
                new PhoneUniqueRule(new Venue(), $this->venue['phone_code'] ?? null, 'phone_number')
            ],
            'venue.email' => [
                'nullable',
                'sometimes',
                'email_custom',
                'unique:venues,email,NULL,deleted_at',
                'string',
            ],
            'venue.bio' => [
                'nullable',
                'string',
            ],
            'user.email' => [
                'email_custom',
                'required',
                'unique:users,email,NULL,deleted_at',
            ],
            'user.first_name' => [
                'string',
                'required',
            ],
            'user.last_name' => [
                'string',
                'required',
            ],
            'user.phone' => [
                'string',
                new PhoneUniqueRule(new User(), $this->user['phone_code'] ?? null, 'phone'),
                'required',
            ],
            'user.phone_code' => [
                'required',
                'int',
            ],
            'venue.start_open_time' => [
                'required',
                'date_format:H:s:i'
            ],
            'venue.end_open_time' => [
                'required',
                'date_format:H:s:i',
                new CompareTwoTimeRule($this->venue['start_open_time'] ?? null)
            ],
            'venue.rules' => [
                'nullable',
                'string'
            ],
            'venue.safety' => [
                'nullable',
                'string'
            ],
            'type' => [
                'required',
                'exists:venue_type,id'
            ],
            'venue.location' => [
                'required',
                'string'
            ]
        ];
    }
}
