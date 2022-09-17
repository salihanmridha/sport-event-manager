<?php

namespace App\Http\Livewire\Relationship;

use App\Models\ContactRelationship;
use Livewire\Component;

class Edit extends Component
{
    public ContactRelationship $contactRelationship;


    public function mount(ContactRelationship $contactRelationship)
    {
        $this->contactRelationship = $contactRelationship;
    }

    public function submit()
    {
        $this->validate();
        $this->contactRelationship->save();

        return redirect()->route('admin.relationship.index');
    }

    public function render()
    {
        return view('livewire.relationship.edit');
    }

    protected function Rules(): array
    {
        return [
            'contactRelationship.code' => [
                'unique:contact_relationship,code',
                'required_without',
            ],
            'contactRelationship.name' => [
                'string',
            ],
            'contactRelationship.creator_id' => [
                'integer',
                'exists:users,id',
                'required',
            ],
        ];
    }
}
