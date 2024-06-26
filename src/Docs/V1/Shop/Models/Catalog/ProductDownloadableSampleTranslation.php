<?php

namespace NexaMerchant\Apis\Docs\V1\Shop\Models\Catalog;

/**
 * @OA\Schema(
 *     title="ProductDownloadableSampleTranslation",
 *     description="ProductDownloadableSampleTranslation model",
 * )
 */
class ProductDownloadableSampleTranslation
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
    public $id;

    /**
     * @OA\Property(
     *     title="Locale",
     *     description="Locale code",
     *     example="en"
     * )
     *
     * @var string
     */
    protected $locale;

    /**
     * @OA\Property(
     *     title="Title",
     *     description="Sample title based on the locale",
     *     example="Sample One"
     * )
     *
     * @var string
     */
    protected $title;

    /**
     * @OA\Property(
     *     title="Product Downloadable Sample ID",
     *     description="Downloadable product's sample ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var int
     */
    public $product_downloadable_sample_id;
}
