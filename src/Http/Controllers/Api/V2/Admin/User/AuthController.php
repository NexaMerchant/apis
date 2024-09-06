<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\User;

use NexaMerchant\Apis\Http\Controllers\Api\V2\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Webkul\User\Repositories\AdminRepository;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use NexaMerchant\Apis\Http\Resources\Api\V2\Admin\Settings\UserResource;

class AuthController extends AdminController
{
    use SendsPasswordResetEmails;

    /**
     * Login user.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request, AdminRepository $adminRepository)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if(!EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            $request->validate(['device_name' => 'required']);

            $admin = $adminRepository->where('email', $request->email)->first();

            if (
                !$admin
                || !Hash::check($request->password, $admin->password)
            ) {
                return $this->fails(trans('Apis::app.admin.account.error.credential-error'), 401);

            }
            /**
             * Preventing multiple token creation.
             */
            $admin->tokens()->delete();

            $data = [
                'data'    => new UserResource($admin),
                'token'  => $admin->createToken($request->device_name, ['role:admin'])->plainTextToken,
            ];

            return $this->success(trans('Apis::app.admin.account.logged-in-success'), $data, 200);
        }

        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();

            return $this->success(trans('Apis::app.admin.account.logged-in-success'), new UserResource($this->resolveAdminUser($request)));
        }

        return $this->fails(trans('Apis::app.admin.account.error.invalid'), 401);

    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        return response()->json([
            'message' => 'Password reset link sent to your email',
        ]);
    }
}