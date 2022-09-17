<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EventStatusEnum;
use App\Models\Event;
use App\Models\Sport;
use App\Models\User;
use Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HomeController
{
    public function __construct(private User $user, private Event $event, private Sport $sport)
    {
    }

    public function index(): Factory|View|Application
    {
        //Administrator
        if(Gate::allows('user_management_access') && Gate::allows('event_management_access')){
            return view('admin.home', [
                'countUser' => $this->user->users()->count(),
                'countAdmin' => $this->user->admins()->count(),
                'countEventOnGoing' => $this->event->countEventByStatus(EventStatusEnum::on_going()),
                'countEventComplete' => $this->event->countEventByStatus(EventStatusEnum::completed()),
                'countEventCancel' => $this->event->countEventByStatus(EventStatusEnum::canceled()),
                'countSport' => $this->sport->count(),
            ]);
        }
        // User management
        if(Gate::allows('user_management_access')){
            return view('admin.home', [
                'countUser' => $this->user->users()->count(),
                'countAdmin' => $this->user->admins()->count(),
                'countSport' => $this->sport->count(),
            ]);
        }

        // Event management
        if(Gate::allows('event_management_access')){
            return view('admin.home', [
                'countEventOnGoing' => $this->event->countEventByStatus(EventStatusEnum::on_going()),
                'countEventComplete' => $this->event->countEventByStatus(EventStatusEnum::completed()),
                'countEventCancel' => $this->event->countEventByStatus(EventStatusEnum::canceled()),
                'countSport' => $this->sport->count(),
            ]);
        }

        return view('admin.home', ['countSport' => $this->sport->count(),]);

    }
}
