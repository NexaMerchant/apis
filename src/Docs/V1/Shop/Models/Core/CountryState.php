<?php

namespace NexaMerchant\Apis\Docs\V1\Shop\Models\Core;

/**
 * @OA\Schema(
 *     title="CountryState",
 *     description="CountryState model",
 * )
 */
class CountryState
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
     *     title="Country Code",
     *     description="Country code",
     *     example="IN"
     * )
     *
     * @var string
     */
    public $country_code;

    /**
     * @OA\Property(
     *     title="Code",
     *     description="State code",
     *     example="UP"
     * )
     *
     * @var string
     */
    public $code;

    /**
     * @OA\Property(
     *     title="Default Name",
     *     description="State default name",
     *     example="Uttar Pradesh"
     * )
     *
     * @var string
     */
    public $default_name;

    /**
     * @OA\Property(
     *     title="Country ID",
     *     description="Country ID",
     *     format="int64",
     *     example=106
     * )
     *
     * @var int
     */
    public $country_id;

    /**
     * @OA\Property(
     *     title="Translations",
     *     description="State translations"
     * )
     *
     * @var \NexaMerchant\Apis\Docs\V1\Shop\Models\Core\CountryStateTranslation
     */
    public $translations;
}
