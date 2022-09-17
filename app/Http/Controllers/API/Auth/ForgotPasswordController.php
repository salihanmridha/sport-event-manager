<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\ForgotPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails {
        sendResetLinkResponse as baseSendResetLinkResponse;
        sendResetLinkFailedResponse as baseSendResetLinkFailedResponse;
    }

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     * @throws ValidationException
     * @unauthenticated
     * @group Guest
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request): JsonResponse
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    protected function sendResetLinkResponse(Request $request, $response): JsonResponse
    {
        $this->baseSendResetLinkResponse($request, $response);

        return response()->json([
            'message' => __($response),
        ]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response): JsonResponse
    {
        $this->baseSendResetLinkFailedResponse($request, $response);

        return response()->json([
            'message' => __($response),
        ]);
    }
}
