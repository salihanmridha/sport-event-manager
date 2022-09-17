<?php

namespace App\Http\Controllers\API\Currency;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CurrenciesController extends Controller
{
    public function index(Request $request)
    {
        $currencies = Currency::all();

        return CurrencyResource::collection($currencies);
    }

    public function show($id)
    {
        $currencies = Currency::find($id);

        return new CurrencyResource($currencies);
    }
}
