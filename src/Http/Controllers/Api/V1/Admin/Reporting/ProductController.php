<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Reporting;

use Nicelizhi\Manage\Helpers\Reporting;

class ProductController extends ReportingController
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
        'total-sold-quantities'            => 'getTotalSoldQuantitiesStats',
        'total-products-added-to-wishlist' => 'getTotalProductsAddedToWishlistStats',
        'top-selling-products-by-revenue'  => 'getTopSellingProductsByRevenue',
        'top-selling-products-by-quantity' => 'getTopSellingProductsByQuantity',
        'products-with-most-reviews'       => 'getProductsWithMostReviews',
        'products-with-most-visits'        => 'getProductsWithMostVisits',
        'last-search-terms'                => 'getLastSearchTerms',
        'top-search-terms'                 => 'getTopSearchTerms',
    ];
}
