<?php

namespace App\Http\Controllers\API\Country;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Country;
use Validator;
use App\Http\Resources\CountryResource;
use Illuminate\Support\Facades\DB;

class CountryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListAllCountry(Request $request)
    {
        $input = $request->all();
        // $limit = isset($input['perpage']) ? $input['perpage'] : 300;

        $results = Country::all();
        return $this->sendResponse(
            CountryResource::collection($results),
            'success'
        );
    }

    public function getDetailCountry($id)
    {
        $product = Country::find($id);

        if (empty($product)) {
            return $this->sendError('Countries not found.');
        }

        return $this->sendResponse(new CountryResource($product), 'Success');
    }
}
