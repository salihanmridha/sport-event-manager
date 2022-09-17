<?php

namespace App\Http\Livewire\Relationship;

use Livewire\Component;
use App\Models\ContactRelationship;

class Create extends Component
{
    public ContactRelationship $contactRelationship;

    public function mount(ContactRelationship $contactRelationship)
    {
        $this->contactRelationship = $contactRelationship;
    }

    public function render()
    {
        return view('livewire.relationship.create');
    }

    public function submit()
    {
        $this->validate();
        $this->contactRelationship->creator_id = auth()->user()->id;
        $this->contactRelationship->save();

        return redirect()->route('admin.relationship.index');
    }

    protected function Rules(): array
    { 
        return [
            'contactRelationship.code' =>[
                'unique:contact_relationship,code',
                'regex:/^\S*$/',
            ],
            'contactRelationship.name' =>[
                'string',
            ],
            'contactRelationship.creator_id' =>[
                'integer',
                'exists:users,id',
                'required',
            ],
        ];
    }
}
