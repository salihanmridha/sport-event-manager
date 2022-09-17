<?php

namespace App\Http\Livewire\Relationship;

use Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Response;
use App\Http\Livewire\WithSorting;
use App\Models\ContactRelationship;
use App\Http\Livewire\WithConfirmation;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public array $selected = [];

    public int $perPage;

    public string $search = '';

    public array $paginationOptions;

    public array $orderable;

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
    
    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new ContactRelationship())->orderable;
    }

    public function render()
    {
        $query = ContactRelationship::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $contactRelationships = $query->paginate($this->perPage);

        return view('livewire.relationship.index',compact('contactRelationships', 'query'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('relationship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ContactRelationship::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(ContactRelationship $contactRelationship)
    {
        abort_if(Gate::denies('relationship_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $contactRelationship->delete();
    }
}
