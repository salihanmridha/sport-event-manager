<?php

namespace App\Http\Controllers\API\BookMark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\BookMark;
use App\Models\Event;
use App\Models\Post;
use App\Models\User;
use App\Models\Venue;
use Validator;
use App\Http\Resources\BookMarkResource;

const TARGET_TYPE = ['event', 'post', 'user', 'venue'];

class BookMarkController extends BaseController
{
    const TARGET_TYPE_ARRAY = ['event', 'post', 'user', 'venue'];
    public function addBookMark(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:event,post,user,venue',
            'target_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        $target_id = $request->input('target_id');
        $user_id = auth()->user()->id;
        $response = null;
        $type = strtolower($request->input('type'));
        try {
            $dataInsert = [
                'user_id' => $user_id,
                'target_id' => $target_id,
                'target_type' => $type,
            ];
            switch ($type) {
                case TARGET_TYPE[0]:
                    $existEvent = Event::find($target_id);
                    if (empty($existEvent)) {
                        return $this->sendError('Event not found');
                    }
                    break;
                case TARGET_TYPE[1]:
                    $existPost = Post::find($target_id);
                    if (empty($existPost)) {
                        return $this->sendError('Post not found');
                    }
                    break;
                case TARGET_TYPE[2]:
                    $existUser = User::find($target_id);
                    if (empty($existUser)) {
                        return $this->sendError('User not found');
                    }
                    break;
                case TARGET_TYPE[3]:
                    $existVenue = Venue::find($target_id);
                    if (empty($existVenue)) {
                        return $this->sendError('Venue not found');
                    }
                    break;
                default:
                    return $this->sendError('type required');
                    break;
            }

            $existEventBookmark = BookMark::where(
                'user_id',
                $dataInsert['user_id']
            )
                ->where('target_id', $dataInsert['target_id'])
                ->where('target_type', $dataInsert['target_type'])
                ->first();
            if (!empty($existEventBookmark)) {
                return $this->sendError($type . ' already exists in bookmark');
            }
            $response = BookMark::create($dataInsert);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->sendResponse(new BookMarkResource($response), 'success');
    }

    public function deleteBookMark(Request $request, $target_id)
    {
        $remove = false;
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:event,post,user,venue',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }

        $type = strtolower($request->input('type'));
        try {
            $remove = BookMark::where('target_id', $target_id)
                ->where('target_type', $type)
                ->where('user_id', auth()->user()->id)
                ->first();
            if (empty($remove)) {
                return $this->sendError('bookmark not found');
            }

            if (auth()->user()->id != $remove->user_id) {
                return $this->sendError('User does not have permission.');
            }
            $remove = BookMark::where('target_id', $target_id)
                ->where('target_type', $type)
                ->where('user_id', auth()->user()->id)
                ->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
        if ($remove) {
            return response()->json(['success' => true], 200);
        } else {
            return response()->json(['success' => false], 404);
        }
    }
    //API list bookmark Event past/Event active/user/venue/post
    public function getBookmark($type, $status = '')
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        // return $user->user_interections;

        if (empty($user)) {
            return $this->sendError('User Not Found!');
        }

        if (!in_array($type, SELF::TARGET_TYPE_ARRAY)) {
            return $this->sendError('Target Type Not Found.');
        }

        $bookmark = null;
        try {
            $where = [];

            if ($type == SELF::TARGET_TYPE_ARRAY[0] && $status === 'active') {

                $where = [['status', '=', 'on_going'], ['end_date_time', '>=', now()]];
                $bookmark = Bookmark::with(['event_bookmark'])
                                    ->whereHas('event_bookmark', function($q) use($where) {
                                            $q->where($where);
                                    })
                                    ->where('user_id', $user_id)
                                    ->where('target_type', '=', $type)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();

            }elseif ($type == SELF::TARGET_TYPE_ARRAY[0] && $status === 'past') {

                $where = [['status', '!=', 'cancel'], ['end_date_time', '<', now()]];
                $bookmark = Bookmark::with(['event_bookmark'])
                                    ->whereHas('event_bookmark', function($q) use($where) {
                                            $q->where($where);
                                    })
                                    ->where('user_id', $user_id)
                                    ->where('target_type', '=', $type)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
            }else {

                $bookmark = Bookmark::with([$type.'_bookmark'])->where('user_id', $user_id)
                                    ->where('target_type', '=', $type)
                                    ->orderBy('created_at', 'DESC')
                                    ->get();
            }


            if (empty($bookmark)) {
                return $this->sendError('Bookmark not found!');
            }
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred');
        }

        return $this->sendResponse(BookmarkResource::collection($bookmark), 'success');
    }
}
