<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Settings;

use Illuminate\Support\Facades\Event;
use Nicelizhi\Manage\Http\Requests\InventorySourceRequest;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Settings\InventorySourceResource;

class InventorySourceController extends SettingController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return InventorySourceRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return InventorySourceResource::class;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(InventorySourceRequest $inventorySourceRequest)
    {
        Event::dispatch('inventory.inventory_source.create.before');

        $data = $inventorySourceRequest->only([
            'code',
            'name',
            'description',
            'latitude',
            'longitude',
            'priority',
            'contact_name',
            'contact_email',
            'contact_number',
            'contact_fax',
            'country',
            'state',
            'city',
            'street',
            'postcode',
            'status',
        ]);

        $inventorySource = $this->getRepositoryInstance()->create($data);

        Event::dispatch('inventory.inventory_source.create.after', $inventorySource);

        return response([
            'data'    => new InventorySourceResource($inventorySource),
            'message' => trans('Apis::app.admin.settings.inventory-sources.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(InventorySourceRequest $inventorySourceRequest, int $id)
    {
        Event::dispatch('inventory.inventory_source.update.before', $id);

        if (! $inventorySourceRequest->status) {
            $inventorySourceRequest['status'] = 0;
        }

        $data = $inventorySourceRequest->only([
            'code',
            'name',
            'description',
            'latitude',
            'longitude',
            'priority',
            'contact_name',
            'contact_email',
            'contact_number',
            'contact_fax',
            'country',
            'state',
            'city',
            'street',
            'postcode',
            'status',
        ]);

        $inventorySource = $this->getRepositoryInstance()->update($data, $id);

        Event::dispatch('inventory.inventory_source.update.after', $inventorySource);

        return response([
            'data'    => new InventorySourceResource($inventorySource),
            'message' => trans('Apis::app.admin.settings.inventory-sources.update-success'),
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

        if ($this->getRepositoryInstance()->count() === 1) {
            return response([
                'message' => trans('Apis::app.admin.settings.inventory-sources.error.last-item-delete-success'),
            ], 400);
        }

        Event::dispatch('inventory.inventory_source.delete.before', $id);

        $this->getRepositoryInstance()->delete($id);

        Event::dispatch('inventory.inventory_source.delete.after', $id);

        return response([
            'message' => trans('Apis::app.admin.settings.inventory-sources.delete-success'),
        ]);
    }
}
