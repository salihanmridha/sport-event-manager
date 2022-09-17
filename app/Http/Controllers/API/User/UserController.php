<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\Profiles\ProfileResource;
use App\Models\User;
use Validator;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSearchResource;
use App\Repositories\EloquentUserRepository;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserController extends BaseController
{
    public function __construct(
        private EloquentUserRepository $userRepository
    ) {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;
        $results = [];
        // try {
        $results = User::whereNull('users.deleted_at')
            ->with(['roles', 'getECrelationship'])
            ->paginate($limit);
        // } catch (\Throwable $e) {
        //     return $this->sendError('User Not Found!');
        // }
        $metadata = null;
        if (count($results) > 0) {
            $metadata = $this->resMetaData($results);
        }
        return $this->sendResponse(
            UserResource::collection($results),
            'success',
            $metadata
        );
    }

    public function info($user) {
        $results = null;
        try {
            $results = User::with(['roles', 'country', 'getECrelationship'])->withCount('memberTeams')->find($user->id);
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred');
        }
        return $this->sendResponse(new UserSearchResource($results), 'Success');
    }


    public function infoContent(User $user, Request $request)
    {
        $input = $request->all();
        $union = $this->userRepository->infoContent($user, $input);
        return $this->sendResponse(
            $union,
            'success'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetailUser($id)
    {
        $results = [];
        try {
            $results = User::whereNull('users.deleted_at')
                ->where('users.id', $id)
                ->with(['roles', 'country', 'getECrelationship'])
                ->withCount('memberTeams')
                ->first();
        } catch (\Throwable $e) {
        }

        if (empty($results)) {
            return $this->sendError('User Not Found!');
        }

        return $this->sendResponse(new UserSearchResource($results), 'success');
    }

    /**
     * SEARCH USER BY FULL NAME
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchUser(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;

        $search = trim($input['search']);
        if (is_null($search)) {
            return $this->sendError('search invalid.');
        }
        $results = [];
        try {
            $results = User::where('users.status', 'active')
                ->where(function ($q) use ($search) {
                    $q
                        ->where('email', 'LIKE', '%' . $search . '%')
                        ->orWhere(
                            DB::raw(
                                "CONCAT(users.first_name,' ',users.last_name)"
                            ),
                            'LIKE',
                            '%' . $search . '%'
                        );
                })
                ->with([
                    'roles:id,title',
                    'country:id,name,code,phone_code',
                    'getECrelationship',
                ])
                ->simplePaginate($limit);
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage());
        }

        if (empty($results)) {
            return $this->sendError('User Not Found!');
        }

        return $this->sendResponse(
            UserSearchResource::collection($results),
            'success'
        );
    }

    // UPDATE USER

    public function updateUser(Request $request, $id)
    {
        $user = null;
        try {
            $user = User::find($id);
            $user_id = auth()->user()->id;

            if (empty($user)) {
                return $this->sendError('User Not Found!');
            }
            
            if ($user->id != $user_id) {
                return $this->sendResponse([], 'User does not have permission.');
            }

        } catch (\Throwable $th) {
        }
       
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'ec_fullname' => 'sometimes|required',
            'ec_relationship' => 'sometimes|required|numeric',
            'ec_main_pcode' => 'sometimes|required',
            'ec_main_pnum' => 'sometimes|required',
            'currency_id' => 'sometimes|required',
            'country_id' => 'sometimes|required',
            // 'gender' => 'sometimes|required', bypass
            'first_name' => 'sometimes|required',
            'last_name' => 'sometimes|required',
            // 'birth_date' => 'sometimes|required',
            'phone' => 'sometimes|required',
            'phone_code' => 'sometimes|required',
            'is_notify_email' => 'sometimes|required',
            'is_notify_sms' => 'sometimes|required',
            'is_notify_push' => 'sometimes|required',
            'is_marketing' => 'sometimes|required',
            'avatar' => 'nullable|sometimes|numeric',
            'background_image' => 'nullable|sometimes|numeric',
            'sport_id' => 'nullable|sometimes|required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        
        try {
            if ($request->has('avatar') && !is_null($input['avatar'])) {
                $media_avatar = Media::find($input['avatar']);
                if(!empty($media_avatar)){
                    // remove avatar 
                    Media::where('collection_name', 'user_avatar')->where('model_id', $user->id)->where('id', '!=', $input['avatar'])->delete();
                    $media_avatar->update([
                        'model_id' => $user->id,
                    ]);
                }
            }
            if ($request->has('background_image') && !is_null($input['background_image'])) {
                $media_background_image = Media::find($input['background_image']);
                if(!empty($media_background_image)){
                    // remove avatar
                    Media::where('collection_name', 'user_background_image')->where('model_id', $user->id)->where('id', '!=', $input['background_image'])->delete();
                    $media_background_image->update([
                        'model_id' => $user->id,
                    ]);
                }
            }
            if ($request->has('sport_id') && !is_null($input['sport_id'])) {
                if(count($input['sport_id']) > 0){
                    foreach ($input['sport_id'] as $value) {
                        if(!$user->sports->contains($value)){
                            $user->sports()->attach([$value => [
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]]);
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred');
        }

        $res = false;
        $format_create_time_now = date('Y-m-d H:i:s');
        $userOBJ = new User();
        $dataUpdate = $userOBJ->fill($input)->toArray();
        unset($dataUpdate['email']);
        unset($dataUpdate['email_verified_at']);
        unset($dataUpdate['created_at']);
        unset($dataUpdate['deleted_at']);
        unset($dataUpdate['status']);
        unset($dataUpdate['email_verification_code']);
        unset($dataUpdate['email_verification_attempts']);
        unset($dataUpdate['send_email_verification_code_at']);
        unset($dataUpdate['provider_name']);
        unset($dataUpdate['provider_id']);
        unset($dataUpdate['provider_name']);
        unset($dataUpdate['avatar']);
        unset($dataUpdate['background_image']);
        unset($dataUpdate['gender']);
        unset($dataUpdate['birth_date']);
        $dataUpdate['updated_at'] = $format_create_time_now;
        
        try {
            $res = $user->update($dataUpdate);
            if($res){
                $user = User::with(['roles:id,title',
                    'country:id,name,code,phone_code',
                    'getECrelationship', 'sports'])->withCount('memberTeams')->find($id);
                return $this->sendResponse(
                    new UserSearchResource($user),
                    'success'
                );
            }
        } catch (\Throwable $th) {
        }
        return response()->json(['success' => false], 404);
    }
}
