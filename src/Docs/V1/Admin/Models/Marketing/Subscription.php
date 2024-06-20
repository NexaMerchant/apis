<?php

namespace NexaMerchant\Apis\Docs\V1\Admin\Models\Marketing;

/**
 * @OA\Schema(
 *     title="Subscription",
 *     description="Subscription model",
 * )
 */
class Subscription
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
     *     title="Status",
     *     description="Subscription status",
     *     example=1,
     *     enum={0,1}
     * )
     *
     * @var int
     */
    private $isSubscribed;

    /**
     * @OA\Property(
     *     title="Email",
     *     description="Email",
     *     example="nice.lizhi@gmail.com",
     * )
     *
     * @var string
     */
    private $email;
}
