<?php

namespace App\Http\Livewire\Event;

use App\Models\Event;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Http\Livewire\WithConfirmation;


class ListPlayer extends Component
{
    use WithConfirmation;
    public Event $event;

    public function mount($event)
    {
        $this->event = $event;
    }

    public function render()
    {
        return view('livewire.event.list-player',
        [
            'players' => app(Event::class)->getListPlayer($this->event->id),
            'eventId' => $this->event->id,
            'eventType' => $this->event->event_type
        ]);
    }

    public function delete(Event $event, $userId)
    {
        abort_if(Gate::denies('list_player_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->usersJoined()->wherePivot('user_id', $userId)->detach();
    }
}
