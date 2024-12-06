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

            // need filter 
            'super_attributes' => $this->super_attributes->map(function ($attribute) {
                // need filter the product have the attribute
                return [
                    'id'   => $attribute->id,
                    'name' => $attribute->name,
                    'admin_name' => $attribute->admin_name,
                    'position' => $attribute->position,
                    'options' => $attribute->options->map(function ($option) {
                        return [
                            'id'    => $option->id,
                            'admin_name' => $option->admin_name,
                            'label' => $option->label,
                            'swatch_value' => $option->swatch_value,
                        ];
                    }),
                ];
            }),

            //'resource' => $configurableOption->getOptions($this->resource, $getAllowedVariants),

            // super attributes are the attributes that are used to create variants.

            'variants' => $this->variants->map(function ($variant) {
                return [
                    'id'    => $variant->id,
                    'sku'   => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'name'  => $variant->name,
                    "admin_name" => $variant->admin_name,
                    //'values' => $variant,
                    'images' => ProductImageResource::collection($variant->images),
                    'sku_data' => explode('-', $variant->sku),
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
