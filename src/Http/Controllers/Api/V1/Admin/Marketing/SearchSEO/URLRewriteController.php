<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\SearchSEO;

use Illuminate\Support\Facades\Event;
use Nicelizhi\Manage\Http\Requests\MassDestroyRequest;
use Webkul\Marketing\Repositories\URLRewriteRepository;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\MarketingController;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Marketing\SearchSEO\URLRewriteResource;

class URLRewriteController extends MarketingController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return URLRewriteRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return URLRewriteResource::class;
    }

    /**
     * Store a newly created resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'entity_type'   => 'required:in:category,product,cms_page',
            'request_path'  => 'required',
            'target_path'   => 'required',
            'redirect_type' => 'required|in:301,302',
            'locale'        => 'required|exists:locales,code',
        ]);

        Event::dispatch('marketing.search_seo.url_rewrites.create.before');

        $urlRewrite = $this->getRepositoryInstance()->create(request()->only([
            'entity_type',
            'request_path',
            'target_path',
            'redirect_type',
            'locale',
        ]));

        Event::dispatch('marketing.search_seo.url_rewrites.create.after', $urlRewrite);

        return response([
            'data'    => new URLRewriteResource($urlRewrite),
            'message' => trans('Apis::app.admin.marketing.search-seo.url-rewrites.create-success'),
        ]);
    }

    /**
     * Update the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $this->validate(request(), [
            'entity_type'   => 'required:in:category,product,cms_page',
            'request_path'  => 'required',
            'target_path'   => 'required',
            'redirect_type' => 'required|in:301,302',
            'locale'        => 'required|exists:locales,code',
        ]);

        Event::dispatch('marketing.search_seo.url_rewrites.update.before', $id);

        $urlRewrite = $this->getRepositoryInstance()->update(request()->only([
            'entity_type',
            'request_path',
            'target_path',
            'redirect_type',
            'locale',
        ]), $id);

        Event::dispatch('marketing.search_seo.url_rewrites.update.after', $urlRewrite);

        return response([
            'data'    => new URLRewriteResource($urlRewrite),
            'message' => trans('Apis::app.admin.marketing.search-seo.url-rewrites.update-success'),
        ]);
    }

    /**
     * Remove the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->getRepositoryInstance()->findOrFail($id);

        Event::dispatch('marketing.search_seo.url_rewrites.delete.before', $id);

        $this->getRepositoryInstance()->delete($id);

        Event::dispatch('marketing.search_seo.url_rewrites.delete.after', $id);

        return response([
            'message' => trans('Apis::app.admin.marketing.search-seo.url-rewrites.delete-success'),
        ]);
    }

    /**
     * To mass delete the url rewrites.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $urlRewriteIds = $massDestroyRequest->input('indices');

        try {
            foreach ($urlRewriteIds as $urlRewriteId) {
                Event::dispatch('marketing.search_seo.url_rewrites.delete.before', $urlRewriteId);

                $this->getRepositoryInstance()->delete($urlRewriteId);

                Event::dispatch('marketing.search_seo.url_rewrites.delete.after', $urlRewriteId);
            }

            return response([
                'message' => trans('Apis::app.admin.marketing.search-seo.url-rewrites.mass-operations.delete-success'),
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}