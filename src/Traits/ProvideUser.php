<?php

namespace NexaMerchant\Apis\Traits;

use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

trait ProvideUser
{
    /**
     * Set default auth driver for admin.
     *
     * @return void
     */
    public function setAdminAuthDriver(Request $request)
    {
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            auth()->setDefaultDriver('admin');
        }
    }

    /**
     * Set default auth driver for shop.
     *
     * @return void
     */
    public function setShopAuthDriver(Request $request)
    {
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            auth()->setDefaultDriver('customer');
        }
    }

    public function setAppAuthDriver(Request $request)
    {
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            auth()->setDefaultDriver('app');
        }
    }

    /**
     * Resolve admin user.
     *
     * @return \Webkul\User\Contracts\Admin
     */
    public function resolveAdminUser(Request $request)
    {
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            return auth('admin')->user();
        }

        return $request->user();
    }

    /**
     * Resolve shop user.
     *
     * @return \Webkul\Customer\Contracts\Customer
     */
    public function resolveShopUser(Request $request)
    {
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            return auth('customer')->user();
        }

        return $request->user();
    }

    public function resolveAppUser(Request $request)
    {
        if (EnsureFrontendRequestsAreStateful::fromFrontend($request)) {
            return auth('app')->user();
        }

        return $request->user();
    }
}
