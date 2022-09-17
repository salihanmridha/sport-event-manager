<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!in_array($request->get('eventType'),Event::EVENT_TYPE_VALUE), 404);
        abort_if(Gate::denies('event_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.event.index');
    }

    public function create()
    {
        abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.event.create');
    }

    // public function createSportEvent()
    // {
    //     abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.event.create.create-sports-event');
    // }

    // public function createSession()
    // {
    //     abort_if(Gate::denies('event_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return view('admin.event.create.create-session');
    // }

    public function edit(Event $event)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.event.edit', compact('event'));
    }

    public function show(Event $event, Request $request)
    {
        abort_if($event->event_type != $request->get('eventType'), 404);
        abort_if(Gate::denies('event_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $event->load('sport');

        return view('admin.event.show', compact('event'));
    }

    public function storeMedia(Request $request)
    {
        abort_if(Gate::none(['event_create', 'event_edit']), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Event();
        $model->id = $request->input('model_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('file')->toMediaCollection($request->input('collection_name'));
        $media->wasRecentlyCreated = true;

        return response()->json(compact('media'), Response::HTTP_CREATED);
    }

    public function getListPlayer($event, Request $request)
    {
        abort_if(Gate::denies('list_player_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        abort_if($event->event_type != $request->get('eventType'), 404);

        return view('admin.event.list-player', compact('event'));
    }

    public function invitePlayer(Event $event)
    {
        return view('admin.event.invite-player', compact('event'));
    }
}
