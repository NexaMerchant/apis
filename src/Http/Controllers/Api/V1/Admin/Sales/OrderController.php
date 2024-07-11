<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Sales\OrderResource;
use Webkul\Sales\Repositories\OrderCommentRepository;
use Webkul\Sales\Repositories\OrderRepository;

class OrderController extends SalesController
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
     * Search all the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function findByEmail(Request $request)
    {
        $validatedData = $request->validate([
            'email'           => 'required',
        ]);
        $results = $this->getRepositoryInstance()->findByEmail($request->get('email'));

        return $this->resource()::collection($results);
    }

    /**
     * Cancel action for the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(int $id)
    {
        $result = $this->getRepositoryInstance()->cancel($id);

        return $result
            ? response(['message' => trans('Apis::app.admin.sales.orders.cancel-success')])
            : response(['message' => trans('Apis::app.admin.sales.orders.error.cancel-error')], 500);
    }

    /**
     * Add comment to the order
     *
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request, OrderCommentRepository $orderCommentRepository, int $id)
    {
        $validatedData = $request->validate([
            'comment'           => 'required',
            'customer_notified' => 'sometimes|sometimes',
        ]);

        $data = array_merge($validatedData, ['order_id' => $id]);

        $data['customer_notified'] = $request->has('customer_notified') ? 1 : 0;

        Event::dispatch('sales.order.comment.create.before', $data);

        $comment = $orderCommentRepository->create($data);

        Event::dispatch('sales.order.comment.create.after', $comment);

        return response([
            'data'    => $comment,
            'message' => trans('Apis::app.admin.sales.orders.comments.create-success'),
        ]);
    }
}
