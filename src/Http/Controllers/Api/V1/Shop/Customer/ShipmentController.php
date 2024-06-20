<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Customer;

use NexaMerchant\Apis\Http\Resources\Api\V1\Shop\Sales\ShipmentResource;
use Webkul\Sales\Repositories\ShipmentRepository;

class ShipmentController extends CustomerController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return ShipmentRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return ShipmentResource::class;
    }
}
