<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Core;

use Illuminate\Http\Request;
use Webkul\Core\Repositories\CoreConfigRepository;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\ShopController;
use NexaMerchant\Apis\Http\Resources\V1\Shop\Core\ConfigurationResource;

class CoreController extends ShopController
{
    /**
     * Is resource authorized.
     */
    public function isAuthorized(): bool
    {
        return false;
    }

    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return CoreConfigRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return ConfigurationResource::class;
    }

    /**
     * Get core configs.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCoreConfigs(Request $request)
    {
        $this->validate($request, [
            '_config'   => 'required|array',
            '_config.*' => 'required|string',
        ]);

        $configValues = [];

        foreach ($request->input('_config') as $config) {
            $configValues[$config] = core()->getConfigData($config);
        }

        return response(['data' => $configValues]);
    }
}
