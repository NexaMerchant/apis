<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Sales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Sales\OrderResource;
use Webkul\Sales\Repositories\OrderCommentRepository;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Models\Order;

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

        $order = $request->get('order') ?? 'desc';
        $sort = $request->get('sort') ?? 'id';
        $page = $request->get('page') ?? 1;
        $limit = $request->get('limit') ?? 10;
        $query  = Order::query();

        $query->where('customer_email', $request->get('email'));
        $query->orderBy($sort, $order);
        $query->paginate($limit, ['*'], 'page', $page);

        $results = $query->get();


       // $results = $this->getRepositoryInstance()->findWhere(["customer_email" => $request->get('email')])->orderBy($sort, $order)->paginate($limit, ['*'], 'page', $page);
        
        return $this->resource()::collection($results);
    }

    /**
     * Get Order disputes
     *
     * @return \Illuminate\Http\Response
     */
    public function disputes(Request $request, int $id) {
        $order = $this->getRepositoryInstance()->findOrFail($id);

        return response([
            'data' => $order->disputes,
        ]);
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
