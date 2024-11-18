<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin;

class AdminController extends ResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /**
         * This is for session based authentication.
         * Activated to all the controllers which are inherited from this.
         */
        $this->setAdminAuthDriver(request());
    }

    // success response method.
    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'code'    => $code,
        ];

        return response()->json($response, 200);
    }

    // return error response.
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'code'    => $code,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
