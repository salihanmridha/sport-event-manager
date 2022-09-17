<?php

namespace App\Http\Controllers\API\Comment;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Team;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;
use App\Http\Controllers\API\Help\HelpController;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;

const AUTHOR_TYPE = ['user', 'team'];

class CommentController extends BaseController
{
    public function createComment(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'post_id' => 'required|numeric',
            'reply_id' => 'nullable|sometimes|numeric',
            'team_id' => 'nullable|sometimes|numeric',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        $response = null;
        $author_id = auth()->user()->id;
        $auth_type = AUTHOR_TYPE[0];
        try {
            $existPost = Post::find($input['post_id']);
            if (empty($existPost)) {
                return $this->sendError('Post not found');
            }

            if ($request->has('team_id') && !is_null($input['team_id'])) {
                $existTeam = Team::find($input['team_id']);
                if (empty($existTeam)) {
                    return $this->sendError('Team not found');
                }
                //check permission
                $help = new HelpController();
                $dataCheckPermission = $help->getPermissionRole($existTeam->id);
                if (!in_array('team_post_management', $dataCheckPermission)) {
                    return $this->sendError('User does not have permission.');
                }
                $author_id = $existTeam->id;
                $auth_type = AUTHOR_TYPE[1];
            }
            if ($request->has('reply_id') && !is_null($input['reply_id'])) {
                $existComment = Comment::where('id', $input['reply_id'])
                    ->where('post_id', $input['post_id'])
                    ->first();
                if (empty($existComment)) {
                    return $this->sendError('Comment parent not found');
                }
            }

            // Add Comment
            $dataInsert = [
                'author_id' => $author_id,
                'author_type' => $auth_type,
                'post_id' => $input['post_id'],
                'reply_id' => $input['reply_id'],
                'content' => $input['content'],
                'post_by' => auth()->user()->id,
            ];
            $response = Comment::create($dataInsert);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $this->sendResponse(new CommentResource($response), 'success');
    }

    public function removeComment($id)
    {
        $user_id = auth()->user()->id;
        try {
            $existComment = Comment::find($id);
            if (empty($existComment)) {
                return $this->sendError('Comment not found');
            }

            if (
                $existComment->author_type == AUTHOR_TYPE[0] &&
                $existComment->author_id != $user_id
            ) {
                return $this->sendError('User does not have permission.');
            }
            if ($existComment->author_type == AUTHOR_TYPE[1]) {
                $existTeam = Team::find($existComment->author_id);
                if (empty($existTeam)) {
                    return $this->sendError('Team not found');
                }
                //check permission
                $help = new HelpController();
                $dataCheckPermission = $help->getPermissionRole($existTeam->id);
                if (!in_array('team_post_management', $dataCheckPermission)) {
                    return $this->sendError('User does not have permission.');
                }
            }

            // REMOVE COMMENT
            $existComment->delete();
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json(['success' => true], 200);
    }

    public function updateComment(Request $request, $id)
    {
        $user_id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        $existComment = null;
        try {
            $existComment = Comment::find($id);
            if (empty($existComment)) {
                return $this->sendError('Comment not found');
            }

            if (
                $existComment->author_type == AUTHOR_TYPE[0] &&
                $existComment->author_id != $user_id
            ) {
                return $this->sendError('User does not have permission.');
            }
            if ($existComment->author_type == AUTHOR_TYPE[1]) {
                $existTeam = Team::find($existComment->author_id);
                if (empty($existTeam)) {
                    return $this->sendError('Team not found');
                }
                //check permission
                $help = new HelpController();
                $dataCheckPermission = $help->getPermissionRole($existTeam->id);
                if (!in_array('team_post_management', $dataCheckPermission)) {
                    return $this->sendError('User does not have permission.');
                }
            }

            // Update COMMENT
            $existComment->update([
                'content' => $request->input('content'),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->sendResponse(
            new CommentResource($existComment),
            'success'
        );
    }
}
