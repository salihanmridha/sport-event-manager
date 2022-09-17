<?php

namespace App\Http\Livewire\Event;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Event;
use App\Models\Invite;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class InvitePlayer extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public int $perPage;

    public array $orderable;

    public string $eventType = '';

    public string $search = '';

    public array $selected = [];

    public $model;


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
        'eventType' => [
            'except' => ''
        ]
    ];
    public Event $event;

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

    public function mount(Event $event)
    {
        $this->model = ($event->application_type == 'team') ? new Team() : new User();
        $this->sortBy = 'id';
        $this->event = $event;
        $this->sortDirection = 'desc';
        $this->orderable = $this->model->orderable;
    }

    public function render()
    {
        $eventType = $this->event->event_type;
        $query = $this->model
            ->when($this->event->application_type == 'team', function ($q){
                $q->with('sport:id,name')
                ->with(['teamMember' => function($q){
                    $q->with(['member_role', 'team_member:id,email']);
                }]);
            })
            ->advancedFilter([
                's'               => $this->search ?: null,
                'order_column'    => $this->sortBy,
                'order_direction' => $this->sortDirection,
            ]);

        $players = $query->paginate(20);

        return view('livewire.event.invite-player', compact('players', 'query', 'eventType'));
    }

    public function inviteSelected()
    {
        $infoInvite = [];
        foreach ($this->selected as $key => $targetId)
        {
            // Add info user
            if(($this->event->application_type == 'individual')){
                $user = User::query()->find($targetId);
                $infoInvite[$key]['first_name'] = $user->first_name;
                $infoInvite[$key]['last_name'] = $user->last_name;
                $infoInvite[$key]['email'] = $user->email;
            }
            $infoInvite[$key]['source_id'] = $this->event->id;
            $infoInvite[$key]['source_type'] = 'event';
            $infoInvite[$key]['target_id'] = $targetId;
            $infoInvite[$key]['target_type'] = ($this->event->application_type == 'team') ? 'team' : 'user';
            $infoInvite[$key]['created_at'] = $infoInvite[$key]['updated_at'] = now();

        }

        Invite::query()->insert($infoInvite);

        $this->resetSelected();
    }
}
