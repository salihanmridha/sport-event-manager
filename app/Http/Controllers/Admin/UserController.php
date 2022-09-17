<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rules\Dimensions;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_if(!in_array($request->get('type'), ['apps', 'cms']), 404);
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.user.index');
    }

    public function create(Request $request)
    {      
        abort_if(!in_array($request->get('type'), ['apps', 'cms']), 404);
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.user.create');
    }

    public function edit(User $user, Request $request)
    {
        abort_if(!in_array($request->get('type'), ['apps', 'cms']), 404);
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.user.edit', compact('user'));
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.user.show', compact('user'));
    }

    public function storeMedia(Request $request)
    {
        abort_if(Gate::none(['user_create', 'user_edit']), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new User();
        $model->id = $request->input('model_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('file')->toMediaCollection($request->input('collection_name'));
        $media->wasRecentlyCreated = true;

        return response()->json(compact('media'), Response::HTTP_CREATED);
    }
}
