<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V2\Admin;

use Illuminate\Http\Request;
use NexaMerchant\Apis\Contracts\ResourceContract;
use NexaMerchant\Apis\Http\Controllers\Api\V2\V2Controller as Controller;
use NexaMerchant\Apis\Traits\ProvideResource;
use NexaMerchant\Apis\Traits\ProvideUser;

class ResourceController extends Controller implements ResourceContract
{
    use ProvideResource, ProvideUser;

    /**
     * Resource name.
     *
     * Can be customizable in individual controller to change the resource name.
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
     * @return \Illuminate\Http\Response
     */
    public function getResource(int $id)
    {
        $resourceClassName = $this->resource();

        $resource = $this->getRepositoryInstance()->findOrFail($id);

        return new $resourceClassName($resource);
    }
}
