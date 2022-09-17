<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\URL;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return JsonResponse
     */
    public function sendResponse($result, $message, $metadata = null)
    {
        $response = [
            'data' => $result,
            'message' => $message,
            'metadata' => $metadata,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function resMetaData($data = [])
    {
        if (count($data) <= 0) {
            return null;
        }
        $pagination_count = isset($data['last_page'])
            ? $data['last_page']
            : null;
        $first_page_url = isset($data['first_page_url'])
            ? $data['first_page_url']
            : null;
        $last_page_url = isset($data['last_page_url'])
            ? $data['last_page_url']
            : null;
        $next_page_url = isset($data['next_page_url'])
            ? $data['next_page_url']
            : null;
        $prev_page_url = isset($data['prev_page_url'])
            ? $data['prev_page_url']
            : null;
        $limit = isset($data['per_page']) ? $data['per_page'] : 0;
        $offset = isset($data['current_page']) ? $data['current_page'] : 0;
        $pagination_total = isset($data['total']) ? $data['total'] : 0;
        return [
            'pagination-count' => $pagination_count,
            'pagination-page' => $offset,
            'pagination-limit' => $limit,
            'pagination-total' => $pagination_total,
            'first' => $first_page_url,
            'previous' => $prev_page_url,
            'next' => $next_page_url,
            'last' => $last_page_url,
        ];
    }
}
