<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\SearchSEO;

use Illuminate\Support\Facades\Event;
use Nicelizhi\Manage\Http\Requests\MassDestroyRequest;
use Webkul\Marketing\Repositories\SearchSynonymRepository;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\MarketingController;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Marketing\SearchSEO\SearchSynonymResource;

class SearchSynonymController extends MarketingController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return SearchSynonymRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return SearchSynonymResource::class;
    }


    /**
     * Store a newly created resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'  => 'required',
            'terms' => 'required',
        ]);

        Event::dispatch('marketing.search_seo.search_synonyms.create.before');

        $searchSynonym = $this->getRepositoryInstance()->create(request()->only([
            'name',
            'terms',
        ]));

        Event::dispatch('marketing.search_seo.search_synonyms.create.after', $searchSynonym);

        return response([
            'data'    => new SearchSynonymResource($searchSynonym),
            'message' => trans('Apis::app.admin.marketing.search-seo.search-synonyms.create-success'),
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
            'name'  => 'required',
            'terms' => 'required',
        ]);

        Event::dispatch('marketing.search_seo.search_synonyms.update.before', $id);

        $searchSynonym = $this->getRepositoryInstance()->update(request()->only([
            'name',
            'terms',
        ]), $id);

        Event::dispatch('marketing.search_seo.search_synonyms.update.after', $searchSynonym);

        return response([
            'data'    => new SearchSynonymResource($searchSynonym),
            'message' => trans('Apis::app.admin.marketing.search-seo.search-synonyms.update-success'),
        ]);
    }

    /**
     * Remove the specified resource.
     * 
     *  @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->getRepositoryInstance()->findOrFail($id);

        Event::dispatch('marketing.search_seo.search_synonyms.delete.before', $id);

        $this->getRepositoryInstance()->delete($id);

        Event::dispatch('marketing.search_seo.search_synonyms.delete.after', $id);

        return response([
            'message' => trans('admin::app.marketing.search-seo.search-synonyms.index.edit.delete-success'),
        ]);
    }

    /**
     * Mass delete the search Synonyms.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $searchSynonymIds = $massDestroyRequest->input('indices');

        try {
            foreach ($searchSynonymIds as $searchSynonymId) {
                Event::dispatch('marketing.search_seo.search_synonyms.delete.before', $searchSynonymId);

                $this->getRepositoryInstance()->delete($searchSynonymId);

                Event::dispatch('marketing.search_seo.search_synonyms.delete.after', $searchSynonymId);
            }

            return response([
                'message' => trans('Apis::app.admin.marketing.search-seo.search-synonyms.mass-operations.delete-success'),
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
