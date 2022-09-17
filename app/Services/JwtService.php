<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class JwtService {
    public function __construct()
    {
    }

    public function generateJwtToken($payload): string
    {
        // Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        // Create the token payload
        $payload = json_encode($payload);

        // Encode Header
        $base64UrlHeader = $this->base64UrlEncode($header);

        // Encode Payload
        $base64UrlPayload = $this->base64UrlEncode($payload);

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, env('JWT_SECRET'), true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // return JWT
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Verify token
     *
     * @param $jwt
     * @return bool|array|string
     */
    public function verifyJwtToken($jwt): bool|array|string
    {
        try{
            // split the token
            $tokenParts = explode('.', $jwt);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signatureProvided = $tokenParts[2];

            // check the expiration time
            $expiration = Carbon::createFromTimestamp(json_decode($payload)->exp);
            $tokenExpired = ($expiration->gte(Carbon::now()->subMinutes(30)));

            // build a signature based on the header and payload using the secret
            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlPayload = $this->base64UrlEncode($payload);
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, env('JWT_SECRET'), true);

            $base64UrlSignature = $this->base64UrlEncode($signature);

            // verify it matches the signature provided in the token
            $signatureValid = ($base64UrlSignature === $signatureProvided);

            if (!$tokenExpired || !$signatureValid) {
                return [false, null];
            }

            return [true, json_decode($payload, true)];
        } catch (\Exception $exception){
            Log::error($exception);

            return [false, null];
        }
    }

    function base64UrlEncode($text): array|string
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
}
