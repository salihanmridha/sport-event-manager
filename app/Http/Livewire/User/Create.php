<?php

namespace App\Http\Livewire\User;

use App\Models\ContactRelationship;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Create extends Component
{
    public User $user;

    public array $roles = [];

    public string $password = '';

    public array $listsForFields = [];

    // public array $country_id = [];

    // public array $phone_code = [];

    // public array $currency_id = [];

    public array $mediaToRemove = [];

    public array $mediaCollections = [];

    public string $type = '';

    public function addMedia($media): void
    {
        $this->mediaCollections[$media['collection_name']][] = $media;
    }

    public function removeMedia($media): void
    {
        $collection = collect(
            $this->mediaCollections[$media['collection_name']]
        );
        $this->mediaCollections[
            $media['collection_name']
        ] = $collection
            ->reject(fn($item) => $item['uuid'] === $media['uuid'])
            ->toArray();
        $this->mediaToRemove[] = $media['uuid'];
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->initListsForFields();
        $arr_status = $this->user::STATUS_SELECT;
        if (!empty($arr_status)) {
            $selected_key = array_key_first($arr_status);
            $this->user->status = $selected_key;
        }

        $this->user->is_notify_email = false;
        $this->user->is_notify_sms = false;
        $this->user->is_notify_push = false;
        $this->user->is_marketing = false;
    }

    public function render()
    {
        return view('livewire.user.create');
    }

    public function submit()
    {
        $this->validate();
        $this->user->password = $this->password;
        $this->user->save();
        $this->user->roles()->sync($this->roles);
        // $this->user->country_id()->sync($this->country_id);
        $this->syncMedia();

        return redirect()->route('admin.users.index', ['type' => $this->type]);
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)
            ->flatten(1)
            ->each(
                fn($item) => Media::where('uuid', $item['uuid'])->update([
                    'model_id' => $this->user->id,
                ])
            );

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        $avatar = [];
        $avatarExist = [];
        $user_background_image = [];
        $user_background_imageExist = [];
        if ($this->type != 'cms') {
            $avatar = ['array', 'nullable'];
            $avatarExist = ['integer', 'exists:media,id'];
            $user_background_image = ['array', 'nullable'];
            $user_background_imageExist = ['integer', 'exists:media,id'];
        }
        return [
            'user.email' => ['email:rfc', 'required', 'unique:users,email'],
            'password' => ['string', 'required'],
            'roles' => ['required', 'array'],
            'roles.*.id' => ['integer', 'exists:roles,id'],
            'user.locale' => ['string', 'nullable'],
            'mediaCollections.user_avatar' => $avatar,
            'mediaCollections.user_avatar.*.id' => $avatarExist,
            'user.first_name' => ['string', 'required'],
            'user.last_name' => ['string', 'required'],
            'user.gender' => [
                'required',
                'in:' .
                implode(',', array_keys($this->listsForFields['gender'])),
            ],
            'user.phone' => ['string', 'required'],
            'user.bio' => ['string', 'nullable'],
            'user.birth_date' => [
                'required',
                'date_format:' . config('project.date_format'),
            ],
            'user.status' => [
                'required',
                'in:' .
                implode(',', array_keys($this->listsForFields['status'])),
            ],
            'mediaCollections.user_background_image' => $user_background_image,
            'mediaCollections.user_background_image.*.id' => $user_background_imageExist,
            'user.country_id' => ['integer'],
            'user.phone_code' => ['required'],

            'user.ec_main_pcode' => ['integer', 'nullable'],

            'user.ec_alt_pcode' => ['integer', 'nullable'],
            'user.currency_id' => ['integer'],
            'user.is_notify_email' => ['boolean'],
            'user.is_notify_sms' => ['boolean'],
            'user.is_notify_push' => ['boolean'],
            'user.is_marketing' => ['boolean'],
            'user.ec_email' => ['email:rfc', 'nullable'],
            'user.ec_fullname' => ['string', 'nullable'],
            'user.ec_main_pnum' => ['nullable'],
            'user.ec_alt_pnum' => ['nullable'],
            'user.ec_relationship' => ['nullable'],
        ];
    }

    protected function initListsForFields(): void
    {
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $this->type = 'cms';
            if ($_GET['type'] == 'apps') {
                $this->listsForFields['roles'] = Role::where('type', 0)
                    ->where('title', 'User')
                    ->pluck('title', 'id')
                    ->toArray();
                // $this->roles = array_keys($this->listsForFields['roles']);
                $this->type = 'apps';
            } else {
                $this->listsForFields['roles'] = Role::where('type', 0)
                    ->where('title', '!=', 'User')
                    ->pluck('title', 'id')
                    ->toArray();
                // $this->roles = array_keys($this->listsForFields['roles']);
            }
        } else {
            $this->listsForFields['roles'] = Role::pluck(
                'title',
                'id'
            )->toArray();
        }
        $this->listsForFields['gender'] = $this->user::GENDER_SELECT;
        $this->listsForFields['status'] = $this->user::STATUS_SELECT;
        $this->listsForFields['country_id'] = Country::pluck(
            'name',
            'id'
        )->toArray();
        $this->listsForFields['phone_code'] = Country::pluck(
            'phone_code',
            'id'
        )->toArray();
        $this->listsForFields['currency_id'] = Currency::pluck(
            'name',
            'id'
        )->toArray();
        $this->listsForFields['ec_relationship'] = ContactRelationship::pluck(
            'name',
            'id'
        )->toArray();
    }
}
