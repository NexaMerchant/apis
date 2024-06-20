<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Core;

use Webkul\Core\Repositories\CurrencyRepository;
use NexaMerchant\Apis\Http\Resources\V1\Shop\Core\CurrencyResource;

class CurrencyController extends CoreController
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
        return CurrencyRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return CurrencyResource::class;
    }
}
