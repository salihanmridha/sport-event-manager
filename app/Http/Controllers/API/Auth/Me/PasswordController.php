<?php

namespace App\Http\Controllers\API\Auth\Me;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\API\Auth\Me\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends BaseController
{
    public function change(ChangePasswordRequest $request)
    {
        $currentPass = $request->user()->password;
        if (!Hash::check($request->input('old_password'), $currentPass)) {
            return $this->sendError('Invalid password');
        }
        $request->user()->password = bcrypt($request->new_password);
        $request->user()->save();
        return $this->sendResponse([], 'Success');
    }
}
