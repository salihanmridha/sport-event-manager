<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Gate;

class VenueController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('venue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venue.index');
    }

    public function create()
    {
        abort_if(Gate::denies('venue_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venue.create');
    }

    public function edit(Venue $venue)
    {
        // Check user can access
        abort_if(Gate::denies('venue_edit') && Gate::denies('venue_edit_own'),
            Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check user can access their venue
        abort_if(Gate::denies('venue_edit') && (Gate::allows('venue_edit_own') &&
            auth()->id() != $venue->owner_id), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.venue.edit', compact('venue'));
    }

    public function show(Venue $venue)
    {
        // Check user can access
        abort_if(Gate::denies('venue_edit') && Gate::denies('venue_edit_own'),
            Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check user can access their venue
        abort_if(Gate::denies('venue_edit') && (Gate::allows('venue_edit_own') &&
                auth()->id() != $venue->owner_id), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venue.show', compact('venue'));
    }

    public function vanueCourts(Venue $venue, $id)
    {
        // Check user can access
        abort_if(Gate::denies('venue_edit') && Gate::denies('venue_edit_own'),
            Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check user can access their venue
        abort_if(Gate::denies('venue_edit') && (Gate::allows('venue_edit_own') &&
                auth()->id() != $venue->owner_id), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $venue = Venue::with("court")->where("id", $id)->first();
        return view('admin.venue.courts', compact('venue'));
    }

    public function storeMedia(Request $request)
    {
        abort_if(Gate::none(['venue_edit', 'venue_create']), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Venue();
        $model->id = $request->input('model_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('file')->toMediaCollection($request->input('collection_name'));
        $media->wasRecentlyCreated = true;

        return response()->json(compact('media'), Response::HTTP_CREATED);
    }
}
