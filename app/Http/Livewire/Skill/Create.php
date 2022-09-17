<?php

namespace App\Http\Livewire\Skill;

use App\Models\TeamLevel;
use Livewire\Component;
use Validator;

class Create extends Component
{
    public TeamLevel $skill;
    public function mount(TeamLevel $skill)
    {
        $this->skill = $skill;
    }

    public function render()
    {
        return view('livewire.skill.create');
    }

    public function submit()
    {
        $this->validate();
        $this->skill->creator_id = auth()->user()->id;
        $this->skill->save();

        return redirect()->route('admin.skills.index');
    }

    protected function rules(): array
    {
        return [
            'skill.name' => [
                'string',
                'required',
                'nullable',
            ],
            'skill.code' =>[
                'unique:team_level,code',
                'regex:/^\S*$/',
            ],

        ];
    }
}
