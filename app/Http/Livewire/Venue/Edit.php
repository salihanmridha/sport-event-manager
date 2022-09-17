<?php

namespace App\Http\Livewire\Venue;

use App\Enums\VenueStatus;
use App\Models\Country;
use App\Models\User;
use App\Models\Venue;
use App\Models\VenueType;
use App\Models\WeekDay;
use App\Rules\CompareTwoTimeRule;
use App\Rules\PhoneUniqueRule;
use Gate;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    public Venue $venue;
    public array $listsForFields = [];

    public array $workdays = [];

    public array $mediaToRemove = [];

    public array $mediaCollections = [];

    public string $emailOwner;

    public function render()
    {
        return view('livewire.venue.edit');
    }

    public function mount(Venue $venue)
    {
        $this->initListsForFields();
        $this->emailOwner = User::findOrFail($venue->owner_id)?->email;
        $this->workdays = $venue->workdays()->pluck('code')->toArray();
        $this->venue = $venue;
        $this->type = $venue->type;
        $this->mediaCollections = [
            'upload_photo' => $venue->upload_photo,
            'banner' => $venue->pictures,
        ];
    }

    public function submit()
    {
        $items = $this->validate();
        $this->venue->type = $items['type'];
        $this->venue->save();
        $this->venue->weekdays()->sync($items['workdays']);
        $this->syncMedia();


        return Gate::check('venue_edit') ? redirect()->route('admin.venues.index') :
            redirect()->route('admin.venues.show', $this->venue);
    }

    protected function initListsForFields()
    {
        $this->listsForFields['phone_code'] = Country::pluck('phone_code', 'id')->toArray();
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();
        $this->listsForFields['status'] = VenueStatus::toArray();
        $this->listsForFields['workdays'] = WeekDay::pluck('name', 'code')->toArray();
        $this->listsForFields['venueType'] = VenueType::pluck('name', 'id')->toArray();
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
                new PhoneUniqueRule(
                    new Venue(),
                    $this->venue['phone_code'] ?? null,
                    'phone_number', $this->venue->id
                )
            ],
            'venue.email' => [
                'nullable',
                'sometimes',
                'email_custom',
                'unique:venues,email,'.$this->venue->id.',id,deleted_at,NULL',
                'string',
            ],
            'venue.bio' => [
                'nullable',
                'string',
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
            'venue.status' => [
                'required',
                'in:0,1'
            ],
            'venue.location' => [
                'required',
                'string'
            ]
        ];
    }
}
