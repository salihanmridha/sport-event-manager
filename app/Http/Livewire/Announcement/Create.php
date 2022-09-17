<?php

namespace App\Http\Livewire\Announcement;

use App\Models\Announcement;
use App\Models\Sport;
use Livewire\Component;
use App\Models\User;
use DateTime;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Create extends Component
{
    public Announcement $announcement;

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



    public function mount(Announcement $announcement)
    {
        $this->announcement = $announcement;
        $this->initListsForFields();

        if (in_array($announcement->type , array_keys($this->listsForFields['type']))) {
            $this->announcement->type = $announcement->type;
        } else {
            $this->announcement->type = "news";
        }

        if (in_array($announcement->status, array_keys($this->listsForFields['status']))) {
            $this->announcement->status = $announcement->status;
        } else {
            $this->announcement->status = "unpublish";
        }

        $this->announcement->start_date = now();
        $date = new DateTime($this->announcement->start_date);
        $this->announcement->end_date = $date->modify('+24 hours');
    }

    public function render()
    {
        return view('livewire.announcement.create');
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
        $this->announcement->creator_id = auth()->user()->id;
        $this->announcement->save();
        $this->syncMedia();

        return redirect()->route('admin.announcements.index');
    }

    protected function syncMedia(): void
    {
        collect($this->mediaCollections)->flatten(1)
            ->each(fn ($item) => Media::where('uuid', $item['uuid'])
                ->update(['model_id' =>  $this->announcement->id]));

        Media::whereIn('uuid', $this->mediaToRemove)->delete();
    }

    protected function rules(): array
    {
        return [

            'announcement.type' => [
                'string',
                'in:' . implode(',', array_keys($this->listsForFields['type'])),
            ],
            'announcement.status' => [
                'string',
                'in:' . implode(',', array_keys($this->listsForFields['status'])),
            ],
            'announcement.title' => [
                'string',
                'required',
            ],
            'announcement.about' => [
                'string',
                'nullable',
            ],
            'announcement.start_date' => [
                'string',
                'date_format:' . config('project.datetime_format'),
                'after_or_equal:now',
            ],
            'announcement.end_date' => [
                'string',
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
