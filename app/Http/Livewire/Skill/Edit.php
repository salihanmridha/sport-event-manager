<?php

namespace App\Http\Livewire\Skill;

use App\Models\TeamLevel;
use Livewire\Component;

class Edit extends Component
{
    public TeamLevel $skill;

    public function mount(TeamLevel $skill)
    {

        $this->skill = $skill;
    }

    public function render()
    {
        return view('livewire.skill.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->skill->save();

        return redirect()->route('admin.skills.index');
    }

    protected function rules(): array
    {
        return [
            'skill.name' => [
                'string',
                'nullable',
            ],
            'skill.code' => [
                'unique:team_level,code,'.$this->skill->id,
                'regex:/^\S*$/',
            ],

        ];
    }
}
