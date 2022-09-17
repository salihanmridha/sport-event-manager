<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateProfileInformationForm extends Component
{
    use AuthorizesRequests;

    public array $listsForFields = [];
    public array $mediaToRemove = [];

    public array $mediaCollections = [];

    public function mount()
    {
        $this->mediaCollections = [
            'user_avatar' => auth()->user()->avatar,
            'user_background_image' => auth()->user()->background_image,
        ];

        $this->user = auth()->user()->withoutRelations()->toArray();

        $this->initListsForFields();
    }

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

    public function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => auth()->id()]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    public function updateProfileInformation()
    {
        $this->authorize('auth_profile_edit');

        $this->resetErrorBag();
        $validatedData = $this->validate();
        User::where('id', auth()->id())->update($validatedData['user']);
        $this->syncMedia();
        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.update-profile-information-form');
    }

    protected $validationAttributes = [
        'user.name'  => 'name',
        'user.email' => 'email',
        'user.first_name' => 'first name',
        'user.last_name' => 'last name',
        'user.gender' => 'gender',
        'user.phone' => 'phone',
        'user.bio' => 'bio',
        'user.birth_date' => 'birth date',
        'user.country_id' => 'country',
        'user.phone_code' => 'phone code',
        'user.currency_id' => 'currency',
    ];
    protected function rules()
    {
        return [
            'user.email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . auth()->id(),
            ],
            'mediaCollections.user_avatar' => [
                'array',
                'nullable',
            ],
            'mediaCollections.user_avatar.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'user.first_name' => [
                'string',
                'required',
            ],
            'user.last_name' => [
                'string',
                'required',
            ],
            'user.gender' => [
                'required',
                'in:' . implode(',', array_keys($this->listsForFields['gender'])),
            ],
            'user.phone' => [
                'string',
                'required',
            ],
            'user.bio' => [
                'string',
                'nullable',
            ],
            'user.birth_date' => [
                'required',
                'date_format:' . config('project.date_format'),
            ],
            'mediaCollections.user_background_image' => [
                'array',
                'nullable',
            ],
            'mediaCollections.user_background_image.*.id' => [
                'integer',
                'exists:media,id',
            ],
            'user.country_id' => [
                'integer'
            ],
            'user.phone_code' => [
                'integer'
            ],
            'user.currency_id' => [
                'integer'
            ],
            'user.is_notify_email' => [
                'boolean'
            ],
            'user.is_notify_sms' => [
                'boolean'
            ],
            'user.is_notify_push' => [
                'boolean'
            ],
            'user.is_marketing' => [
                'boolean'
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['gender'] = auth()->user()::GENDER_SELECT;
        $this->listsForFields['country_id'] = Country::pluck('name', 'id')->toArray();
        $this->listsForFields['phone_code'] = Country::pluck('phone_code', 'id')->toArray();
        $this->listsForFields['currency_id'] = Currency::pluck('name','id')->toArray();
    }
}
