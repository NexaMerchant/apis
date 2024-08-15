<?php

namespace NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

class DisputeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'order_id'             => $this->order_id,
            'dispute_id'           => $this->dispute_id,
            'platform'             => $this->platform,
            'transaction_id'       => $this->transaction_id,
            'status'               => $this->status,
            // 'order_increment_id'   => $this->order_increment_id,
            // 'order_status'         => $this->order_status,
            // 'order_status_label'   => $this->order_status_label,
            // 'dispute_type'         => $this->dispute_type,
            // 'dispute_type_label'   => $this->dispute_type_label,
            // 'dispute_status'       => $this->dispute_status,
            // 'dispute_status_label' => $this->dispute_status_label,
            // 'dispute_created_at'   => $this->created_at,
            // 'dispute_updated_at'   => $this->updated_at,
            // 'dispute_closed_at'    => $this->closed_at,
            // 'dispute_reason'       => $this->reason,
            // 'dispute_description'  => $this->description,
            // 'dispute_images'       => $this->images,
            // 'dispute_refund'       => $this->refund,
            // 'dispute_refund_label' => core()->formatPrice($this->refund, $this->order->order_currency_code),
            // 'dispute_refund_base'  => $this->refund,
            // 'dispute_refund_base_label' => core()->formatBasePrice($this->refund),
            // 'dispute_refund_status' => $this->refund_status,
            // 'dispute_refund_status_label' => $this->refund_status_label,
            // 'dispute_refund_created_at' => $this->refund_created_at,
            // 'dispute_refund_updated_at' => $this->refund_updated_at,
            // 'dispute_refund_closed_at' => $this->refund_closed_at,
            // 'dispute_refund_reason' => $this->refund_reason,
            // 'dispute_refund_description' => $this->refund_description,
            // 'dispute_refund_images' => $this->refund_images,
            // 'dispute_refund_amount' => $this->refund_amount,
            // 'dispute_refund_amount_label' => core()->formatPrice($this->refund_amount, $this->order->order_currency_code),
            // 'dispute_refund_base_amount' => $this->refund_amount,
            // 'dispute_refund_base_amount_label' => core()->formatBasePrice($this->refund_amount),
            // 'dispute_refund_transaction_id' => $this->refund_transaction_id,
            // 'dispute_refund_transaction_status' => $this->refund_transaction_status,
            // 'dispute_refund_transaction_created_at' => $this->refund_transaction_created_at,
            // 'dispute_refund_transaction_updated_at' => $this->refund_transaction_updated_at,
            // 'dispute_refund_transaction_closed_at' => $this->refund_transaction_closed_at,
            // 'dispute_refund_transaction_reason' => $this->refund_transaction_reason,
            // 'dispute_refund_transaction_description' => $this->refund_transaction_description,
            // 'dispute_refund_transaction_images' => $this->refund_transaction_images,
            // 'dispute_refund_transaction_amount' => $this->refund_transaction_amount,
            // 'dispute_refund_transaction_amount_label' => core()->formatPrice($this->refund_transaction_amount, $this->order->order_currency_code),
            // 'dispute_refund_transaction_base_amount' => $this->refund_transaction_amount,
            // 'dispute_refund_transaction_base_amount_label' => core()->formatBasePrice($this->refund_transaction_amount),
            // 'dispute_refund_transaction_payment_id' => $this->refund_transaction_payment_id,
            // 'dispute_refund_transaction_payment_status' => $this->refund_transaction_payment_status,
            // 'dispute_refund_transaction_payment_created_at' => $this->refund_transaction_payment_created_at,
            // 'dispute_refund_transaction_payment_updated_at' => $this->refund_transaction_payment_updated_at,
            // 'dispute_refund_transaction_payment_closed_at' => $this->refund_transaction_payment_closed_at,
            // 'dispute_refund_transaction_payment_reason' => $this->refund_transaction_payment_reason,
            // 'dispute_refund_transaction_payment_description' => $this->refund_transaction_payment_description,
            // 'dispute_refund_transaction_payment_images' => $this->refund_transaction_payment_images,
            // 'dispute_refund_transaction_payment_amount' => $this->refund_transaction_payment_amount,
            // 'dispute_refund_transaction_payment_amount_label' => core()->formatPrice($this->refund_transaction_payment_amount, $this->order->order_currency_code),
            // 'dispute_refund_transaction_payment_base_amount' => $this->refund_transaction_payment_amount,
            // 'dispute_refund_transaction_payment_base_amount_label' => core()->formatBasePrice($this->refund_transaction_payment_amount),
            // 'dispute_refund_transaction_payment_transaction_id' => $this->refund_transaction_payment_transaction_id,
            // 'dispute_refund_transaction_payment_transaction_status' => $this->refund_transaction_payment_transaction_status,
            // 'dispute_refund_transaction_payment_transaction_created_at' => $this->refund_transaction_payment_transaction_created_at,
            // 'dispute_refund_transaction_payment_transaction_updated_at' => $this->refund_transaction_payment_transaction_updated_at,
            // 'dispute_refund_transaction_payment_transaction_closed_at' => $this->refund_transaction_payment_transaction_closed_at,
            // 'dispute_refund_transaction_payment_transaction_reason' => $this->refund_transaction_payment_transaction_reason,
            // 'dispute_refund_transaction_payment_transaction_description' => $this->refund_transaction_payment_transaction_description,
            // 'dispute_refund_transaction_payment_transaction_images' => $this->refund_transaction_payment_transaction_images,
            // 'dispute_refund_transaction_payment_transaction_amount' => $this->refund_transaction_payment_transaction_amount,
        ];
    }
}