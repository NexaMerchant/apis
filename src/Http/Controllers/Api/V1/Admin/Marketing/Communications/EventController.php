<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\Communications;

use Illuminate\Support\Facades\Event;
use Webkul\Marketing\Repositories\EventRepository;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\MarketingController;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Marketing\Communications\EventResource;

class EventController extends MarketingController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return EventRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return EventResource::class;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validatedData = $this->validate(request(), [
            'name'        => 'required',
            'description' => 'required',
            'date'        => 'date|required',
        ]);

        Event::dispatch('marketing.events.create.before');

        $event = $this->getRepositoryInstance()->create($validatedData);

        Event::dispatch('marketing.events.create.after', $event);

        return response([
            'data'    => new EventResource($event),
            'message' => trans('Apis::app.admin.marketing.communications.events.create-success'),
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
            'name'        => 'required',
            'description' => 'required',
            'date'        => 'date|required',
        ]);

        Event::dispatch('marketing.events.update.before', $id);

        $event = $this->getRepositoryInstance()->update($validatedData, $id);

        Event::dispatch('marketing.events.update.after', $event);

        return response([
            'data'    => new EventResource($event),
            'message' => trans('Apis::app.admin.marketing.communications.events.update-success'),
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

        Event::dispatch('marketing.events.delete.before', $id);

        $this->getRepositoryInstance()->delete($id);

        Event::dispatch('marketing.events.delete.after', $id);

        return response([
            'message' => trans('Apis::app.admin.marketing.communications.events.delete-success'),
        ]);
    }
}
