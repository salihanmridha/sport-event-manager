<?php

namespace App\Http\Livewire\VenueType;

use App\Models\VenueType;
use Livewire\Component;

class Edit extends Component
{
    public VenueType $venueType;

    public function mount(VenueType $venueType)
    {

        $this->venueType = $venueType;
    }

    public function render()
    {
        return view('livewire.venue-type.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->venueType->save();

        return redirect()->route('admin.venue-types.index');
    }

    protected function rules(): array
    {
        return [
            'venueType.name' => [
                'string',
                'required',
                'nullable',
            ],
            'venueType.code' => [
                'unique:venue_type,code,'.$this->venueType->id,
                'regex:/^\S*$/',
            ],

        ];
    }
}
