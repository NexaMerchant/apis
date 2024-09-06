<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V2\Admin;

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

    protected function fails(string $message, int $code = 400)
    {
        return response()->json([
            'code' =>$code,
            'type' => 'error',
            'message' => $message,
        ]);
    }

    protected function success(string $message, mixed $data = [], int $code = 200)
    {
        return response()->json([
            'code' => $code,
            'type' => 'success',
            'message' => $message,
            'result' => $data,
        ]);
    }
}
