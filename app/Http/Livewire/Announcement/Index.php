<?php

namespace App\Http\Livewire\Announcement;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Announcement;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sport;


class Index extends Component
{

    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public int $perPage;
    public int $filter;

    public array $orderable;

    public string $search = '';

    public array $selected = [];
    public array $listsForFields = [];

    public array $filterArray = [];
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
        $this->filter           = 0;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Announcement())->orderable;
        $this->initListsForFields();
    }

    public function render()
    {
        $where = [];

        switch ($this->filter) {
            case 1:
                $where = [['status', '=', 'publish'],['start_date', '>',now()]];
                break;
            case 2:
                $where =[['status', '=', 'publish'],['start_date', '<=',now()], ['end_date', '>=', now()]];
                break;
            case 3:
                $where = [['status','=', 'publish'], ['end_date', '<', now()]];
                break;
            case 4:
                $where = [['status','=', 'unpublish']];
                break;

            default:
                $where = [];
                break;
        }

        $query = Announcement::with(['creator'])->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ])->where($where);//with(['creator'])
        $announcements = $query->paginate($this->perPage);

        return view('livewire.announcement.index', compact('query', 'announcements'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('announcement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Announcement::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Announcement $announcement)
    {
        abort_if(Gate::denies('announcement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $announcement->delete();
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['status']       = Announcement::STATUS_SELECT;
        $this->listsForFields['sport']            = Sport::pluck('name', 'id')->toArray();
        $this->listsForFields['type'] = Announcement::ANNOUNCEMENT_TYPE_SELECT;
        $this->filterArray = [0 => 'All', 1 => 'Save', 2 => 'Ongoing', 3 => 'Expired', 4 => 'Draft'];

    }
}
