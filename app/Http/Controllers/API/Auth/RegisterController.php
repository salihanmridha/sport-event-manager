<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\API\Auth\RegisterSocialRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @unauthenticated
     * @group Guest
     */
    public function register(RegisterRequest $request)
    {
        // event(new Registered($user = User::createWithAttributes($request->validated())));

        $attibutes = $request->validated();
        $attibutes = array_merge($attibutes,  [
            // 'provider_name' => $provider,
            // 'provider_id' => $providerUser->id,
            'status' => 'active' // will add enum later
        ]);


        // event(new Registered());
        $user = User::createWithAttributes($attibutes);

        $this->_uploadUserAvatar($user, $request);

        return response()->json([
            'token' => $user->createToken(env('APP_NAME'))->plainTextToken,
        ]);
    }

    private function _uploadUserAvatar($user, $request) {
        if ($request->has('avatar_id')) {
            Media::where('id', $request->input('avatar_id'))
                ->update(['model_id' => $user->id]);
        }
    }

    /**
     * @unauthenticated
     * @group Guest
     */
    public function registerSocial(RegisterSocialRequest $request)
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
        if ($user) {
            throw new Exception("User was already exists");
        }
        

        

        $attibutes = $request->validated();
        $attibutes = array_merge($attibutes,  [
            'provider_name' => $provider,
            'provider_id' => $providerUser->id,
            'status' => 'active'
        ]);


        // event(new Registered());
        $user = User::createWithAttributes($attibutes);
        $this->_uploadUserAvatar($user, $request);

        return response()->json([
            'token' => $user->createToken($provider)->plainTextToken,
            'data' => new UserResource($user)
        ]);
    }

    /**
     * @unauthenticated
     * @group Guest
     * @bodyParam field string required The field need to check.
     * @bodyParam value string required The value need to check.
     */
    protected function checkExisting(Request $request)
    {
        $query = User::query();
        $field = $request->input('field');
        $value = $request->input('value');
        $exists = false;
        $execute = false;
        if ($value && $field) {
            $execute = true;
            $query->where($field, 'LIKE', $value);
        } else {
            foreach($request->input() as $cond) {
                $field = $cond['field'];
                $value = $cond['value'];
                if ($value && $field) {
                    $execute = true;
                    $query = $query->where($field, 'LIKE', $value);
                }
            }
        }
        if ($execute) {
            $exists = $query->exists();
        }
        return response()->json([
            'exists' => $exists
        ]);
    }
}
