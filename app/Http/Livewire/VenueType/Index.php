<?php

namespace App\Http\Livewire\VenueType;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\VenueType;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

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
        $this->orderable         = (new VenueType())->orderable;
    }

    public function render()
    {
        $query = VenueType::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $venueTypes = $query->paginate($this->perPage);

        return view('livewire.venue-type.index', compact('venueTypes', 'query'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('venue_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        VenueType::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(VenueType $venueType)
    {
        abort_if(Gate::denies('venue_type_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $venueType->delete();
    }
}
