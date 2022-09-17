<?php

namespace App\Http\Livewire\OrganizationRole;

use Livewire\Component;
use App\Models\OrganizatioRole;

class Edit extends Component
{
    public OrganizatioRole $orgRole;

    public function mount(OrganizatioRole $orgRole)
    {
        $this->orgRole = $orgRole;
    }

    public function render()
    {
        return view('livewire.organization-role.edit');
    }

    public function submit()
    {
        $this->validate();
        $this->orgRole->creator_id = auth()->user()->id;
        $this->orgRole->save();

        return redirect()->route('admin.organization-role.index');
    }

    protected function rules(): array
    {
        return [
            'orgRole.name' => [
                'string',
                'nullable',
            ],
            'orgRole.code' => [
                'unique:organization_role,code,'.$this->orgRole->id,
                'regex:/^\S*$/',
            ],
        ];
    }
}
