<?php

namespace App\Http\Livewire\Permission;

use App\Models\Permission;
use Livewire\Component;

class Edit extends Component
{
    public Permission $permission;
    public array $listsForFields = [];

    public function mount(Permission $permission)
    {
        $this->permission = $permission;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.permission.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->permission->save();

        return redirect()->route('admin.permissions.index');
    }

    protected function rules(): array
    {
        return [
            'permission.title' => [
                'string',
                'required',
            ],
            'permission.type' => [
                'string',
                'required',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['type'] = [
            0 => 'CMS',
            1 => 'APP',
        ];
    }
}
