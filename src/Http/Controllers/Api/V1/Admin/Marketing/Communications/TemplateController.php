<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\Communications;

use Illuminate\Support\Facades\Event;
use Webkul\Marketing\Repositories\TemplateRepository;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\MarketingController;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Marketing\Communications\TemplateResource;

class TemplateController extends MarketingController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return TemplateRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return TemplateResource::class;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validatedData = $this->validate(request(), [
            'name'    => 'required',
            'status'  => 'required|in:active,inactive,draft',
            'content' => 'required',
        ]);

        Event::dispatch('marketing.templates.create.before');

        $template = $this->getRepositoryInstance()->create($validatedData);

        Event::dispatch('marketing.templates.create.after', $template);

        return response([
            'data'    => new TemplateResource($template),
            'message' => trans('Apis::app.admin.marketing.communications.templates.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(int $id)
    {
        $validatedData = $this->validate(request(), [
            'name'    => 'required',
            'status'  => 'required|in:active,inactive,draft',
            'content' => 'required',
        ]);

        Event::dispatch('marketing.templates.update.before', $id);

        $template = $this->getRepositoryInstance()->update($validatedData, $id);

        Event::dispatch('marketing.templates.update.after', $template);

        return response([
            'data'    => new TemplateResource($template),
            'message' => trans('Apis::app.admin.marketing.communications.templates.update-success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->getRepositoryInstance()->findOrFail($id);

        Event::dispatch('marketing.templates.delete.before', $id);

        $this->getRepositoryInstance()->delete($id);

        Event::dispatch('marketing.templates.delete.after', $id);

        return response([
            'message' => trans('Apis::app.admin.marketing.communications.templates.delete-success'),
        ]);
    }
}
