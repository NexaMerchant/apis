<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Catalog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Nicelizhi\Manage\Http\Requests\InventoryRequest;
use Nicelizhi\Manage\Http\Requests\MassDestroyRequest;
use Nicelizhi\Manage\Http\Requests\MassUpdateRequest;
use Nicelizhi\Manage\Http\Requests\ProductForm;
use Webkul\Core\Rules\Slug;
use Webkul\Product\Helpers\ProductType;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Catalog\ProductResource;

class ProductController extends CatalogController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return ProductRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return ProductResource::class;
    }

    /**
     * 
     * Quick Create a new product.
     * 
     * @return \Illuminate\Http\Response
     */
    public function quickCreate(Request $request){
        $request->validate([
            'sku'                 => ['required', 'unique:products,sku', new Slug],
        ]);

        $req = $request->all();
        $input = [];
        $input['sku'] = $req['sku'];
        $input['type'] = 'configurable';
        $input['attribute_family_id'] = 1;
        $super_attributes = [];

        // create super attributes and check if the attribute is valid
        $attributeRepository = app('Webkul\Attribute\Repositories\AttributeRepository');
        foreach ($req['options'] as $attribute) {

            //var_dump($attribute['values']);
            
            $attributeRepos = $attributeRepository->findOneByField('code', $attribute['name']);
            //var_dump($attributeRepos);exit;
            if(!$attributeRepos){
                // attribute not found and create a new attribute
                $attributeRepos = $attributeRepository->create([
                    'code' => $attribute['name'],
                    'admin_name' => $attribute['name'],
                    'type' => 'select',
                    'is_required' => 1,
                    'is_unique' => 0,
                    'validation' => '',
                    'position' => $attribute['position'],
                    'is_visible' => 1,
                    'is_configurable' => 1,
                    'is_filterable' => 1,
                    'is_filterable_in_search' => 1,
                    'is_filterable_in_list' => 1,
                    'use_in_flat' => 1,
                    'is_comparable' => 1,
                    'is_used_for_promo_rules' => 1,
                    'is_visible_on_front' => 1,
                    'swatch_type' => 'dropdown',
                    'use_in_product_listing' => 1,
                    'use_in_comparison' => 1,
                    'is_user_defined' => 1,
                    'value_per_locale' => 0,
                    'value_per_channel' => 0,
                    'channel_based' => 0,
                    'locale_based' => 0,
                    'default_value' => ''
                ]);
            }
            // check if the attribute option is valid
            $attributeOptionRepository = app('Webkul\Attribute\Repositories\AttributeOptionRepository');
            $attributeOptionArray = [];
            foreach ($attribute['values'] as $option) {
                $attributeOption = $attributeOptionRepository->findOneByField(['admin_name'=>$option, 'attribute_id'=>$attributeRepos->id]);
                if(!$attributeOption){
                    $attributeOption = $attributeOptionRepository->create([
                        'admin_name' => $option,
                        'sort_order' => $attribute['position'],
                        'attribute_id' => $attributeRepos->id
                    ]);
                }
                //var_dump($attributeOption->admin_name);
                $attributeOptionArray[$attributeOption->id] = $attributeOption->id;

                Log::info('Attribute Option: ' . json_encode($attributeOption));
            }
            
            // var_dump($attributeOptionArray);
            // var_dump($attributeRepos->id);

            $super_attributes[$attributeRepos->code] = $attributeOptionArray;
            
        }

        //var_dump($super_attributes);exit;

        $input['super_attributes'] = $super_attributes;

        //var_dump($input);exit;

        Event::dispatch('catalog.product.create.before');

        $product = $this->getRepositoryInstance()->create($input);

        Event::dispatch('catalog.product.create.after', $product);

        $id = $product->id;

        $multiselectAttributeCodes = [];
        $productAttributes = $this->getRepositoryInstance()->findOrFail($id);

        $data = $request->all();
        
        foreach ($productAttributes->attribute_family->attribute_groups as $attributeGroup) {
            $customAttributes = $productAttributes->getEditableAttributes($attributeGroup);

            if (count($customAttributes)) {
                foreach ($customAttributes as $attribute) {
                    if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
                        array_push($multiselectAttributeCodes, $attribute->code);
                    }
                }
            }
        }

        if (count($multiselectAttributeCodes)) {
            foreach ($multiselectAttributeCodes as $multiselectAttributeCode) {
                if (! isset($data[$multiselectAttributeCode])) {
                    $data[$multiselectAttributeCode] = [];
                }
            }
        }

        Event::dispatch('catalog.product.update.before', $id);

        $variants = $variantCollection = $product->variants()->get()->toArray();

        $tableData = [];
        $skus = $request->input('tableData');

        var_dump($skus);exit;
        

        $product = $this->getRepositoryInstance()->update($data, $id);

        Event::dispatch('catalog.product.update.after', $product);

        return response([
            'data'    => new ProductResource($product),
            'message' => trans('Apis::app.admin.catalog.products.create-success'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (
            ProductType::hasVariants($request->input('type'))
            && (! $request->has('super_attributes')
                || ! count($request->get('super_attributes')))
        ) {
            return response([
                'message' => trans('rest-api::app.admin.catalog.products.error.configurable-error'),
            ], 400);
        }

        $request->validate([
            'type'                => 'required',
            'attribute_family_id' => 'required',
            'sku'                 => ['required', 'unique:products,sku', new Slug],
        ]);

        Event::dispatch('catalog.product.create.before');

        $product = $this->getRepositoryInstance()->create($request->all());

        Event::dispatch('catalog.product.create.after', $product);

        return response([
            'data'    => new ProductResource($product),
            'message' => trans('rest-api::app.admin.catalog.products.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProductForm $request, int $id)
    {
        $data = $request->all();

        $multiselectAttributeCodes = [];

        $productAttributes = $this->getRepositoryInstance()->findOrFail($id);

        foreach ($productAttributes->attribute_family->attribute_groups as $attributeGroup) {
            $customAttributes = $productAttributes->getEditableAttributes($attributeGroup);

            if (count($customAttributes)) {
                foreach ($customAttributes as $attribute) {
                    if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
                        array_push($multiselectAttributeCodes, $attribute->code);
                    }
                }
            }
        }

        if (count($multiselectAttributeCodes)) {
            foreach ($multiselectAttributeCodes as $multiselectAttributeCode) {
                if (! isset($data[$multiselectAttributeCode])) {
                    $data[$multiselectAttributeCode] = [];
                }
            }
        }
        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->getRepositoryInstance()->update($data, $id);

        Event::dispatch('catalog.product.update.after', $product);

        return response([
            'data'    => new ProductResource($product),
            'message' => trans('Apis::app.admin.catalog.products.update-success'),
        ]);
    }

    /**
     * Update inventories.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateInventories(InventoryRequest $inventoryRequest, ProductInventoryRepository $productInventoryRepository, int $id)
    {
        $product = $this->getRepositoryInstance()->findOrFail($id);

        Event::dispatch('catalog.product.update.before', $id);

        $productInventoryRepository->saveInventories($inventoryRequest->all(), $product);

        Event::dispatch('catalog.product.update.after', $product);

        return response()->json([
            'data'    => [
                'total' => $productInventoryRepository->where('product_id', $product->id)->sum('qty'),
            ],
            'message' => trans('Apis::app.admin.catalog.products.inventories.update-success'),
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

        Event::dispatch('catalog.product.delete.before', $id);

        $this->getRepositoryInstance()->delete($id);

        Event::dispatch('catalog.product.delete.after', $id);

        return response([
            'message' => trans('Apis::app.admin.catalog.products.delete-success'),
        ]);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest)
    {
        $productIds = $massDestroyRequest->input('indices');

        foreach ($productIds as $productId) {
            Event::dispatch('catalog.product.delete.before', $productId);

            $this->getRepositoryInstance()->delete($productId);

            Event::dispatch('catalog.product.delete.after', $productId);
        }

        return response([
            'message' => trans('Apis::app.admin.catalog.products.mass-operations.delete-success'),
        ]);
    }

    /**
     * Mass update the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest)
    {
        foreach ($massUpdateRequest->indices as $id) {
            $this->getRepositoryInstance()->findOrFail($id);

            Event::dispatch('catalog.product.update.before', $id);

            $product = $this->getRepositoryInstance()->update([
                'channel' => null,
                'locale'  => null,
                'status'  => $massUpdateRequest->value,
            ], $id);

            Event::dispatch('catalog.product.update.after', $product);
        }

        return response([
            'message' => trans('Apis::app.admin.catalog.products.mass-operations.update-success'),
        ]);
    }

    /**
     * Upload product images.
     *
     * @return \Illuminate\Http\Response
     */

    public function upload(Request $request)
    {
        $this->upload($request->all(), 'images');

        return response([], 201);

    }
}
