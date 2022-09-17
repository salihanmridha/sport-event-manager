<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VenueBooking;
use Illuminate\Http\Response;
use Gate;

class ReservationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('venue_reservation_access_own'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.reservation.index');
    }

    public function show(VenueBooking $reservation)
    {
        abort_if(Gate::denies('venue_reservation_access_own'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.reservation.response', compact('reservation'));
    }

}
