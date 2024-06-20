<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Core;

use Webkul\Core\Repositories\ChannelRepository;
use NexaMerchant\Apis\Http\Resources\V1\Shop\Core\ChannelResource;

class ChannelController extends CoreController
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
        return ChannelRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return ChannelResource::class;
    }
}
