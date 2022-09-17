<?php

namespace App\Http\Livewire\VenueType;

use App\Models\VenueType;
use Livewire\Component;
use Validator;

class Create extends Component
{
    public VenueType $venueType;
    public function mount(VenueType $venueType)
    {
        $this->venueType = $venueType;
    }

    public function render()
    {
        return view('livewire.venue-type.create');
    }

    public function submit()
    {
        $this->validate();
        $this->venueType->creator_id = auth()->user()->id;
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
            'venueType.code' =>[
                'unique:venue_type,code',
                'regex:/^\S*$/',
            ],

        ];
    }
}
