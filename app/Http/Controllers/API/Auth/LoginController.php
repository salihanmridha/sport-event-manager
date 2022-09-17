<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\SocialLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
    }

    /**
     * @unauthenticated
     * @group Guest
     * @bodyParam login_field string required The login field.
     * @bodyParam password string required The password.
     * @bodyParam device_name string required The device name.
     */
    public function login(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('users.email', $request->input('email'))->first();

        if (isset($user)) {
            if(!\Hash::check($request->input('password'), $user->password)){
                $this->incrementLoginAttempts($request);
                $responseData = ['errors' => __('messages.credential_invalid')];
            } else{
                $responseData = [
                    'token' => $user->createToken($request->input('device_name'))->plainTextToken,
                    'is_email_verified' => (bool) $user->email_verified_at ?? false,
                    'data' => new UserResource($user)
                ];
            }

            return response()->json($responseData);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Social Login
     */
    public function socialLogin(SocialLoginRequest $request)
    {
        $provider = $request->input('provider_name'); // or $request->input('provider_name') for multiple providers
        $token = $request->input('access_token');
        // get the provider's user. (In the provider server)
        if ($provider == 'twitter') {
            $secret = $request->input('access_token_secret');
            $providerUser = Socialite::driver($provider)->userFromTokenAndSecret($token, $secret);
        } else {
            $providerUser = Socialite::driver($provider)->userFromToken($token);
        }

        // check if access token exists etc..
        // search for a user in our server with the specified provider id and provider name
        $user = User::where('provider_name', $provider)->where('provider_id', $providerUser->id)->first();
        // if there is no record with these data, create a new user
        if ($user == null) {
            return response()->json([
                'success' => false,
                'token' => null
            ]);
        }
        // create a token for the user, so they can login
        $token = $user->createToken(env('APP_NAME'))->plainTextToken;
        // return the token for usage
        return response()->json([
            'success' => true,
            'token' => $token,
            'data' => new UserResource($user)
        ]);
    }

}
