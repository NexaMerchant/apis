<?php

namespace NexaMerchant\Apis\Docs\V1\Admin\Models\Sales;

/**
 * @OA\Schema(
 *     title="Shipment",
 *     description="Shipment model",
 * )
 */
class Shipment
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     title="Order's Shipment Status",
     *     description="Order's shipment status",
     *     example="shipped|null"
     * )
     *
     * @var string
     */
    private $status;

    /**
     * @OA\Property(
     *     title="Shipment Total Quantity",
     *     description="Shipment total quantity",
     *     format="int64",
     *     example=2
     * )
     *
     * @var int
     */
    private $total_qty;

    /**
     * @OA\Property(
     *     title="Total Weight Of Shipment",
     *     description="Total weight of shipment",
     *     example="10.30"
     * )
     *
     * @var float
     */
    private $total_weight;

    /**
     * @OA\Property(
     *     title="Carrier/Shipment Code",
     *     description="Carrier/Shipment Code (Shipping Method Code)",
     *     example="free_shipping"
     * )
     *
     * @var string
     */
    private $carrier_code;

    /**
     * @OA\Property(
     *     title="Carrier Title",
     *     description="Carrier Title (Shipping Method Title)",
     *     example="BlueDart"
     * )
     *
     * @var string
     */
    private $carrier_title;

    /**
     * @OA\Property(
     *     title="Tracking ID",
     *     description="Shipment's tracking number",
     *     example="SHP101"
     * )
     *
     * @var string
     */
    private $track_number;

    /**
     * @OA\Property(
     *     title="Shipment Email Sent",
     *     description="Shipment Email Sent Status",
     *     example=true,
     * )
     *
     * @var bool
     */
    private $email_sent;

    /**
     * @OA\Property(
     *     title="Order Shipment's Customer",
     *     description="Order Shipment's Customer"
     * )
     *
     * @var \NexaMerchant\Apis\Docs\V1\Admin\Models\Customer\Customer
     */
    private $customer;

    /**
     * @OA\Property(
     *     title="Inventory Source",
     *     description="Shipment's inventory source",
     * )
     *
     * @var \NexaMerchant\Apis\Docs\V1\Admin\Models\Setting\InventorySource
     */
    private $inventory_source;

    /**
     * @OA\Property(
     *     title="Shipment Items",
     *     description="Shipment items"
     * )
     *
     * @var \NexaMerchant\Apis\Docs\V1\Admin\Models\Sale\ShipmentItem
     */
    private $items;

    /**
     * @OA\Property(
     *     title="Created at",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     description="Updated at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $updated_at;
}
