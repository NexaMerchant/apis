<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop\Core;

use Illuminate\Http\Request;
use Webkul\Core\Repositories\CountryRepository;
use NexaMerchant\Apis\Http\Resources\Api\V1\Shop\Core\CountryResource;

class CountryController extends CoreController
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
        return CountryRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return CountryResource::class;
    }

    /**
     * Get country state group listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountryStateGroups(Request $request)
    {
        $resources = core()->groupedStatesByCountries();

        return response(['data' => $resources]);
    }
}
