<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Customer;

use Illuminate\Http\Request;
use NexaMerchant\Apis\Http\Resources\Api\V1\Shop\Sales\OrderResource;
use Webkul\Sales\Repositories\OrderRepository;

class OrderController extends CustomerController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return OrderRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return OrderResource::class;
    }

    /**
     * Cancel customer's order.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, int $id)
    {
        $order = $this->resolveShopUser($request)->orders()->find($id);

        if ($order && $this->getRepositoryInstance()->cancel($order)) {
            return response([
                'message' => trans('Apis::app.shop.sales.cancel'),
            ]);
        }

        return response([
            'message' => trans('Apis::app.shop.sales.orders.error.cancel-error'),
        ]);
    }
}
