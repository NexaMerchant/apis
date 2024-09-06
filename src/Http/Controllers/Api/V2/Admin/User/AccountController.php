<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User;

class AccountController
{
    public function get()
    {
        return response()->json([
            'data' => auth()->user(),
        ]);
    }

    public function update()
    {
        $user = auth()->user();

        $user->update(request()->all());

        return response()->json([
            'data' => $user,
        ]);
    }
}