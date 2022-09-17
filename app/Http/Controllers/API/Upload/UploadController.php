<?php

namespace App\Http\Controllers\API\Upload;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\API\Upload\UploadRequest;
use App\Models\Sport;
use Validator;
use App\Http\Resources\SportResource;
use App\Models\Event;
use App\Models\User;
use App\Models\Team;
use App\Models\Venue;
use Illuminate\Http\Response;

class UploadController extends BaseController
{
    public function upload(UploadRequest $uploadRequest)
    {
        $type = $uploadRequest->input('type');
        $model = new User();
        $collection = 'user_avatar';
        if ($type == 'event') {
            $model = new Event();
            $collection = 'event';
        }
        if ($type == 'sport') {
            $model = new Sport();
            $collection = 'sport';
        }
        if ($type == 'team_avatar_image') {
            $model = new Team();
            $collection = 'team_avatar_image';
        }
        if ($type == 'team_background') {
            $model = new Team();
            $collection = 'team_background';
        }
        if ($type == 'venue_upload_photo') {
            $model = new Venue();
            $collection = 'upload_photo';
        }
        if ($type == 'venue_banner') {
            $model = new Venue();
            $collection = 'banner';
        }

        if ($type == 'user_background_image') {
            $collection = 'user_background_image';
        }

        $model->id = 0;
        $model->exists = true;
        $media = $model
            ->addMediaFromRequest('file')
            ->toMediaCollection($collection);
        $media->wasRecentlyCreated = true;

        return response()->json(compact('media'), Response::HTTP_CREATED);
    }
}
