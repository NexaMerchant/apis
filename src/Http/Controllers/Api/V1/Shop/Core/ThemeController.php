<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Core;

use NexaMerchant\Apis\Http\Resources\Api\V1\Shop\Core\ThemeResource;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class ThemeController extends CoreController
{
    /**
     * Using const variable for status
     */
    public const STATUS = 1;

    /**
     * Repository class name.
     */
    public function repository():string
    {
        return ThemeCustomizationRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return ThemeResource::class;
    }

    /**
     * Get Theme Customizations listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function getThemeCustomizations()
    {
        $customizations = $this->getRepositoryInstance()->orderBy('sort_order')->findWhere([
            'status'     => self::STATUS,
            'channel_id' => core()->getCurrentChannel()->id,
        ]);

        return response([
            'data'    => $customizations,
        ]);
    }
}
