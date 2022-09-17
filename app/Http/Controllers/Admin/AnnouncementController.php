<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Gate;
use Illuminate\Http\Response;

class AnnouncementController extends Controller
{
    public function index()
    {


        abort_if(Gate::denies('announcement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.announcement.index');
    }

    public function create()
    {
        abort_if(Gate::denies('announcement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.announcement.create');
    }

    public function edit(Announcement $announcement)
    {
        abort_if(Gate::denies('announcement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($announcement->status == 'publish' and $announcement->start_date < now()) {

            return back()->with('error','Access denied!');

        }

        return view('admin.announcement.edit', compact('announcement'));
    }

    public function show(Announcement $announcement)
    {
       // dd($announcement);
        abort_if(Gate::denies('announcement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $announcement->load('sport');
        $statusArray = Announcement::STATUS_SELECT;

        return view('admin.announcement.show', compact('announcement','statusArray' ));
    }

    public function storeMedia(Request $request)
    {

        abort_if(Gate::none(['announcement_media_create', 'user_edit']), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $model = new Announcement();
        $model->id = $request->input('model_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('file')->toMediaCollection($request->input('collection_name'));
        $media->wasRecentlyCreated = true;

        return response()->json(compact('media'), Response::HTTP_CREATED);
    }
}

