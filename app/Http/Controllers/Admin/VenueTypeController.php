<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VenueType;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VenueTypeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('venue_type_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venue-type.index');
    }

    public function create()
    {
        abort_if(Gate::denies('venue_type_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venue-type.create');
    }

    public function edit(VenueType $venueType)
    {

        abort_if(Gate::denies('venue_type_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venue-type.edit', compact('venueType'));
    }

    public function show(VenueType $venueType)
    {
        abort_if(Gate::denies('venue_type_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.venue-type.show', compact('venueType'));
    }
}
