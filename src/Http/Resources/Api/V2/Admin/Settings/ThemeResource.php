<?php

namespace NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            ...$this->resource->toArray(),
        ];
    }
}
