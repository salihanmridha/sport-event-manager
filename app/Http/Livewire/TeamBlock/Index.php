<?php

namespace App\Http\Livewire\TeamBlock;

use App\Models\Team;
use App\Models\TeamBlock;
use Illuminate\Http\Response;
use Gate;
use Livewire\Component;

class Index extends Component
{
    // public string $search = '';

    // public int $perPage;

    // public TeamBlock $teamBlock;

    public array $selected = [];


    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    // public array $orderable;

    // protected $queryString = [
    //     'search' => [
    //         'except' => '',
    //     ],
        // 'sortBy' => [
        //     'except' => 'id',
        // ],
        // 'sortDirection' => [
        //     'except' => 'desc',
        // ],
    // ];

    // public function updatingSearch()
    // {
    //     $this->resetPage();
    // }

    public function mount(Team $team,TeamBlock $teamBlock)
    {
        $this->team = $team;
        $this->teamBlock = $teamBlock;
    }

    public function render()
    {
        // $query = Team::advancedFilter([
        //     's'               => $this->search ?: null,
            // 'order_column'    => $this->sortBy,
            // 'order_direction' => $this->sortDirection,
        // ]);
        $usersBlocked = $this->team->usersBlocked()->get();
        return view('livewire.team-block.index', compact('usersBlocked'));
    }

    // public function deleteSelected()
    // {
    //     abort_if(Gate::denies('team_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     Team::whereIn('id', $this->selected)->delete();

    //     $this->resetSelected();
    // }

    public function delete($userBlockId)
    {
        abort_if(Gate::denies('team_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        TeamBlock::where('team_id', $this->team->id)->where('user_id',$userBlockId)->delete();
    }

}
