<?php

namespace App\Http\Controllers\API\Venue;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\BookMark;
use App\Http\Resources\VenueResource;
use Validator;
use App\Enums\VenueStatus;

define('TYPE_VENUE', 'venue');

class VenueController extends BaseController
{
    public function getAllVenue(Request $request)
    {
        $input = $request->all();
        $limit = isset($input['perpage']) ? $input['perpage'] : 20;
        $q = $request->has('q') ? $input['q'] : null;
        $type = $request->has('type') ? $input['type'] : null;
        $sport_id = $request->has('sport_id') ? $input['sport_id'] : null;
        $country_id = $request->has('country_id') ? $input['country_id'] : null;

        $validator = Validator::make($request->all(), [
            'type' => 'nullable|sometimes|numeric',
            'country_id' => 'nullable|sometimes|numeric',
            'sport_id' => 'nullable|sometimes',
        ]);
        $listSportId = !is_null($sport_id) ? explode(',', $sport_id) : null;
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendError($error);
        }
        $results = null;
        try {
            $results = Venue::with([
                'country:id,name,code,phone_code',
                'type:id,code,name',
                'owner:id,first_name,last_name,phone,gender,birth_date',
                'court:id,venue_id,sport_id,name,price,weight,status',
            ])
                ->where(function ($query) use ($q, $type, $country_id) {
                    if (!is_null($q)) {
                        $query->where('name', 'LIKE', '%' . $q . '%');
                    }
                    if (!is_null($type)) {
                        $query->where('type', $type);
                    }
                    if (!is_null($country_id)) {
                        $query->where('country_id', $country_id);
                    }
                })
                ->whereHas('court', function ($q) use ($listSportId) {
                    if (!is_null($listSportId)) {
                        $q->whereIn('sport_id', $listSportId);
                    }
                })
                ->withCount('court')
                ->where('status', VenueStatus::active())
                ->simplePaginate($limit);
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred.' . $th);
        }
        return $this->sendResponse(
            VenueResource::collection($results),
            'success'
        );
    }

    public function getDetailVenue($id)
    {
        $user_id = auth()->user()->id;
        $results = [];
        try {
            $results = Venue::with([
                'country:id,name,code,phone_code',
                'type:id,code,name',
                'owner:id,first_name,last_name,phone,gender,birth_date',
                'workdays:name,code',
                'court:id,sport_id,name,price,weight,status',
            ])
                ->withCount('court')
                ->where('status', VenueStatus::active())
                ->find($id);

            if (empty($results)) {
                return $this->sendError('Venue not found.');
            }
            $existBookmark = BookMark::where('user_id', $user_id)
                ->where('target_id', $results->id)
                ->where('target_type', TYPE_VENUE)
                ->first();
            $results->is_bookmark = false;
            if (!empty($existBookmark)) {
                $results->is_bookmark = true;
            }
        } catch (\Throwable $th) {
            return $this->sendError('An error occurred.' . $th);
        }

        return $this->sendResponse(new VenueResource($results), 'success');
    }
}
