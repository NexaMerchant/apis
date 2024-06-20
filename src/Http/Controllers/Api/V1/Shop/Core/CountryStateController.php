<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Core;

use Webkul\Core\Repositories\CountryStateRepository;
use NexaMerchant\Apis\Http\Resources\Api\V1\Shop\Core\CountryStateResource;

class CountryStateController extends CoreController
{
    /**
     * Is resource authorized.
     */
    public function isAuthorized(): bool
    {
        return false;
    }

    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return CountryStateRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return CountryStateResource::class;
    }
}
