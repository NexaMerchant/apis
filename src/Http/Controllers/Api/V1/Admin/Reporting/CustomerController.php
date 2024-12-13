<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Reporting;

use Nicelizhi\Manage\Helpers\Reporting;

class CustomerController extends ReportingController
{
    public $reportingHelper;

    public function __construct()
    {
        //parent::__construct();

        $this->reportingHelper = app(Reporting::class);
    }
    /**
     * Request param functions.
     *
     * @var array
     */
    protected $typeFunctions = [
        'total-customers'             => 'getTotalCustomersStats',
        'customers-traffic'           => 'getCustomersTrafficStats',
        'customers-with-most-sales'   => 'getCustomersWithMostSales',
        'customers-with-most-orders'  => 'getCustomersWithMostOrders',
        'customers-with-most-reviews' => 'getCustomersWithMostReviews',
        'top-customer-groups'         => 'getTopCustomerGroups',
    ];
}
