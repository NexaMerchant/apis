<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Shop;

use Illuminate\Http\Request;
use NexaMerchant\Apis\Contracts\ResourceContract;
use NexaMerchant\Apis\Http\Controllers\Api\V1\V1Controller;
use NexaMerchant\Apis\Traits\ProvideResource;
use NexaMerchant\Apis\Traits\ProvideUser;

class ResourceController extends V1Controller implements ResourceContract
{
    use ProvideResource, ProvideUser;

    /**
     * Resource name.
     *
     * @var string
     */
    protected $resourceName = 'Resource(s)';

    /**
     * These are ignored during request.
     *
     * @var array
     */
    protected $requestException = ['page', 'limit', 'pagination', 'sort', 'order', 'token'];

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allResources(Request $request)
    {
        $query = $this->getRepositoryInstance()->scopeQuery(function ($query) use ($request) {
            if ($this->isAuthorized()) {
                $query = $query->where('customer_id', $this->resolveShopUser($request)->id);
            }

            foreach ($request->except($this->requestException) as $input => $value) {
                $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
            }

            if ($sort = $request->input('sort')) {
                $query = $query->orderBy($sort, $request->input('order') ?? 'desc');
            } else {
                $query = $query->orderBy('id', 'desc');
            }

            return $query;
        });

        if (is_null($request->input('pagination')) || $request->input('pagination')) {
            $results = $query->paginate($request->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->getResourceCollection($results);
    }

    /**
     * Returns an individual resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getResource(Request $request, $id)
    {
        $resourceClassName = $this->resource();

        $resource = $this->isAuthorized()
            ? $this->getRepositoryInstance()->where('customer_id', $this->resolveShopUser($request)->id)->findOrFail($id)
            : $this->getRepositoryInstance()->findOrFail($id);

        return new $resourceClassName($resource);
    }
}
