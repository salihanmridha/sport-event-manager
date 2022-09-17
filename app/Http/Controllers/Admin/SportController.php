<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SportController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sport_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sport.index');
    }

    public function create()
    {
        abort_if(Gate::denies('sport_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sport.create');
    }

    public function edit(Sport $sport)
    {
        abort_if(Gate::denies('sport_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sport.edit', compact('sport'));
    }

    public function show(Sport $sport)
    {
        abort_if(Gate::denies('sport_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sport->load('creator');

        return view('admin.sport.show', compact('sport'));
    }
    public function storeMedia(Request $request)
    {
        abort_if(Gate::none(['sport_create', 'sport_edit']), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Sport();
        $model->id = $request->input('model_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('file')->toMediaCollection($request->input('collection_name'));
        $media->wasRecentlyCreated = true;

        return response()->json(compact('media'), Response::HTTP_CREATED);
    }
}
