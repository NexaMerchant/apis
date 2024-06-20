<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Core;

use Webkul\Core\Repositories\LocaleRepository;
use NexaMerchant\Apis\Http\Resources\Api\V1\Shop\Core\LocaleResource;

class LocaleController extends CoreController
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
        return LocaleRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return LocaleResource::class;
    }
}
