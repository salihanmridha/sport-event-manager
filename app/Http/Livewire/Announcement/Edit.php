<?php

namespace App\Http\Livewire\Announcement;

use App\Models\Announcement;
use App\Models\Sport;
use DateTime;
use Illuminate\Validation\Validator;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Edit extends Component
{
    public Announcement $announcement;

    public array $mediaToRemove = [];

    public array $mediaCollections = [];

    public array $listsForFields = [];


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

    public function mount(Announcement $announcement)
    {

        $this->announcement = $announcement;
        $this->initListsForFields();

        if (in_array($announcement->type , array_keys($this->listsForFields['type']))) {
            $this->announcement->type = $announcement->type;
        } else {
            $this->announcement->type = "news";
        }

        $this->mediaCollections = [
            'announcement_background_image' => $announcement->background_image,
        ];
    }

    public function getMediaCollections($name)
    {
        return $this->mediaCollections[$name];
    }

    public function render()
    {

        return view('livewire.announcement.edit');
    }

    public function setEndDate($value)

    {

        $this->announcement->start_date = $value;
        $date = new DateTime($this->announcement->start_date);
        $this->announcement->end_date = $date->modify('+24 hours');

    }

    public function submit()
    {
        $this->validate();

        $this->withValidator(function (Validator $validator) {

            $validator->after(function ($validator) {

                $date = new DateTime($this->announcement->start_date);
                $checkStartDate = $date->modify('+24 hours');

                if ($this->announcement->status == 'publish' and $checkStartDate < now()) {

                    $validator->errors()->add('announcement.start_date', 'Start Time cannot be less then 24 hours from now!');

                }
                if ($this->announcement->status == 'unpublish') {

                    $this->validate(['announcement.start_date' => 'after_or_equal:now']);

                }

            });

        })->validate();

        $this->announcement->save();
        $this->syncMedia();

        return redirect()->route('admin.announcements.index');
    }


    public function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' => $this->announcement->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [
            'announcement.type' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['type'])),
            ],
            'announcement.status' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['status'])),
            ],
            'announcement.title' => [
                'string',
                'nullable',
            ],
            'announcement.about' => [
                'string',
                'nullable',
            ],
            'announcement.start_date' => [
                'nullable',
                'date_format:' . config('project.datetime_format'),
                // 'after_or_equal:now',
            ],
            'announcement.end_date' => [
                'nullable',
                'date_format:' . config('project.datetime_format'),
            ],
            'mediaCollections.announcement_background_image' => [
                'array',
                'nullable',
            ],
            'mediaCollections.announcement_background_image.*.id' => [
                'integer',
                'exists:media,id',
            ],
        ];
    }


    protected function initListsForFields(): void
    {
        $this->listsForFields['status']       = $this->announcement::STATUS_SELECT;
        $this->listsForFields['sport']            = Sport::pluck('name', 'id')->toArray();
        $this->listsForFields['type'] = $this->announcement::ANNOUNCEMENT_TYPE_SELECT;
    }
}
