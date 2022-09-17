<?php

namespace App\Http\Livewire\User;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
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

    protected array $cms_role = [];

    public string $type = '';

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
        $this->sortBy = 'id';
        $this->sortDirection = 'desc';
        $this->perPage = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable = (new User())->orderable;
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $this->type = $_GET['type'];
        }
    }

    public function render(Request $request)
    {
        $query = User::with(['roles'])->advancedFilter([
            's' => $this->search ?: null,
            'order_column' => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        if ($this->type) {
            $this->cms_role = Role::getCmsRoles();
            if ($this->type == 'apps') {
                $query->whereHas('roles', function ($q) {
                    $q->whereNotIn('id', $this->cms_role);
                });
            } elseif ($this->type == 'cms') {
                $query->whereHas('roles', function ($q) {
                    $q->whereIn('id', $this->cms_role);
                });
            } else {
                // Nếu không có value type
                $query->where('id', 0);
            }
        }
        $users = $query->paginate($this->perPage);

        return view('livewire.user.index', compact('query', 'users'));
    }

    public function deleteSelected()
    {
        abort_if(
            Gate::denies('user_delete'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        User::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(User $user)
    {
        abort_if(
            Gate::denies('user_delete'),
            Response::HTTP_FORBIDDEN,
            '403 Forbidden'
        );

        $user->delete();
    }
}
