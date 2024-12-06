<?php

namespace NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * Not able to use individual key in the resource because
         * attributes are system defined and custom defined.
         *
         * @var array
         */
        $mainAttributes = $this->resource->toArray();

        return [
            /**
             * Main attributes.
             */
            ...$mainAttributes,

            'sku' => $this->resource->sku,

            'super_attributes' => $this->super_attributes,

            'variants' => $this->variants->map(function ($variant) {
                return [
                    'id'    => $variant->id,
                    'sku'   => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'images' => ProductImageResource::collection($variant->images),
                ];
            }),

            'categories' => $this->categories->map(function ($category) {
                return [
                    'id'   => $category->id,
                    'name' => $category->name,
                ];
            }),

            /**
             * Additional attributes.
             */
            'images'     => ProductImageResource::collection($this->images),
            'videos'     => ProductVideoResource::collection($this->videos),
            'additional' => $this->additional,
        ];
    }
}
