<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Catalog;

use Webkul\Attribute\Repositories\AttributeRepository;
use NexaMerchant\Apis\Http\Resources\V1\Shop\Catalog\AttributeResource;

class AttributeController extends CatalogController
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
        return AttributeRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return AttributeResource::class;
    }
}
