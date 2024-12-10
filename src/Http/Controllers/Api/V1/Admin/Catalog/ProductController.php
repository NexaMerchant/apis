<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Catalog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Nicelizhi\Manage\Http\Requests\InventoryRequest;
use Nicelizhi\Manage\Http\Requests\MassDestroyRequest;
use Nicelizhi\Manage\Http\Requests\MassUpdateRequest;
use Nicelizhi\Manage\Http\Requests\ProductForm;
use Webkul\Core\Rules\Slug;
use Webkul\Product\Helpers\ProductType;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Product\Repositories\ProductRepository;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Catalog\ProductResource;
use Nicelizhi\Airwallex\Core;

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

    public function getResource(int $id)
    {
        $product = $this->getRepositoryInstance()->findOrFail($id);

        return response([
            'data'    => new ProductResource($product),
            'message' => trans('Apis::app.admin.catalog.products.show-success'),
        ]);
    }

    /**
     * 
     * Quick Create a new product.
     * 
     * @return \Illuminate\Http\Response
     */
    public function quickCreate(Request $request){
        //$request['sku'] = time(); // for test
        $req = $request->all();
        if(empty($req['id'])) {
            $request->validate([
                'sku'=> ['required', 'unique:products,sku', new Slug],
            ]);
        }        

        $input = [];
        $input['sku'] = $req['sku'];
        // $input['sku'] = time();
        $input['type'] = 'configurable';
       // $input['attribute_family_id'] = 1;
        $super_attributes = [];
        $super_attributes_label = []; // super attributes label
        $super_attributes_ids = [];

        // create a family attribute by sku
        $attributeFamilyRepository = app('Webkul\Attribute\Repositories\AttributeFamilyRepository');
        $attributeFamily = $attributeFamilyRepository->findOneByField('code', $req['sku']);

        $attribute_group_id = 0;

        if(!$attributeFamily){
            $attributeFamily = $attributeFamilyRepository->create([
                'code' => $req['sku'],
                'name' => $req['sku'],
                'status' => 1,
                'is_user_defined' => 1
            ]);

            // create a default group for the family
            $attributeGroupRepository = app('Webkul\Attribute\Repositories\AttributeGroupRepository');
            $attributeGroup = DB::table('attribute_groups')->insert([
                    [
                    'name' => 'General',
                    'position' => 1,
                    'column' => 1,
                    'attribute_family_id' => $attributeFamily->id
                ],[
                    'attribute_family_id' => $attributeFamily->id,
                    'name' => 'Description',
                    'column' => 1,
                    'position' => 2
                ],[
                    'attribute_family_id' => $attributeFamily->id,
                    'name' => 'Meta Description',
                    'column' => 1,
                    'position' => 3
                ],[
                    'attribute_family_id' => $attributeFamily->id,
                    'name' => 'Price',
                    'column' => 2,
                    'position' => 1
                ],[
                    'attribute_family_id' => $attributeFamily->id,
                    'name' => 'Shipping',
                    'column' => 2,
                    'position' => 2
                ],[
                    'attribute_family_id' => $attributeFamily->id,
                    'name' => 'Settings',
                    'column' => 2,
                    'position' => 3
                ],[
                    'attribute_family_id' => $attributeFamily->id,
                    'name' => 'Inventories',
                    'column' => 2,
                    'position' => 4
                ]
            ]);

            // base use attribute group add attribute group mappings
            //$attributeGroupMappingRepository = app('Webkul\Attribute\Repositories\AttributeGroupMappingRepository');
            $attributeGroupItems = $attributeGroupRepository->where('attribute_family_id', $attributeFamily->id)->limit(1)->get();

            //var_dump($attributeGroupItems);exit;
            
            foreach($attributeGroupItems as $attributeGroupItem) {
                $attributeGroupMapping = DB::table('attribute_group_mappings')->where("attribute_id", )->where("attribute_group_id", $attributeGroupItem->id)->first();
                if(!$attributeGroupMapping){
                    $attributeMaxID = 32;
                    $attributeGroupMappingDatas = [];
                    for($i=1;$i<=$attributeMaxID;$i++){

                        // check if the attribute group mapping is have the attribute
                        $attributeGroupMappingData = [
                            'attribute_id' => $i,
                            'attribute_group_id' => $attributeGroupItem->id
                        ];

                        $attributeGroupMappingDatas[] = $attributeGroupMappingData;
                    }
                    DB::table('attribute_group_mappings')->insert($attributeGroupMappingDatas);
                    
                }
                $attribute_group_id = $attributeGroupItem->id;
            }
        }else{
            $attributeGroupRepository = app('Webkul\Attribute\Repositories\AttributeGroupRepository');
            $attributeGroup = $attributeGroupRepository->findOneByField('attribute_family_id', $attributeFamily->id);
            $attribute_group_id = $attributeGroup->id;
        }
        $input['attribute_family_id'] = $attributeFamily->id;

        // create super attributes and check if the attribute is valid
        $attributeRepository = app('Webkul\Attribute\Repositories\AttributeRepository');
        foreach ($req['options'] as $attribute) {
            //var_dump($attribute['values']);

            $code = "attr_".$input['attribute_family_id']."_".md5($attribute['name']);
            $super_attributes_label[$attribute['position']] = $code;

             // create a unique code for the attribute
            
            $attributeRepos = $attributeRepository->findOneByField('code', $code);
            //var_dump($attributeRepos);exit;
            if(!$attributeRepos){
                // attribute not found and create a new attribute
                Event::dispatch('catalog.attribute.create.before');
                $attributeRepos = $attributeRepository->create([
                    'code' => $code,
                    'admin_name' => $attribute['name'],
                    'type' => 'select',
                    'is_required' => 0,
                    'is_unique' => 0,
                    'validation' => '',
                    'position' => $attribute['position'],
                    'is_visible' => 1,
                    'is_configurable' => 1,
                    'is_filterable' => 1,
                    'use_in_flat' => 0,
                    'is_comparable' => 0,
                    'is_visible_on_front' => 0,
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
                Event::dispatch('catalog.attribute.create.after', $attribute);
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

                //Log::info('Attribute Option: ' . json_encode($attributeOption));
            }
            $super_attributes[$attributeRepos->code] = $attributeOptionArray;
            $super_attributes_ids[$attributeRepos->id] = $attributeRepos->id;
        }

        $input['super_attributes'] = $super_attributes;
        $input['channel'] = Core()->getCurrentChannel()->code;
        $input['locale'] = Core()->getCurrentLocale()->code;
       

        //add attribut id to attribute_group_mappings
        $attributeGroupMappingRespos = app();
        foreach($super_attributes_ids as $key=>$super_attributes_id) {
            $attribute_group_mapping = DB::table('attribute_group_mappings')->where("attribute_id", $super_attributes_id)->where("attribute_group_id", $attribute_group_id)->first();
            if(!$attribute_group_mapping){
                DB::table('attribute_group_mappings')->insert([
                    'attribute_id' => $super_attributes_id,
                    'attribute_group_id' => $attribute_group_id
                ]);
            }
        }

        if($req['id']){

            // update the product super_attributes


            $product = $this->getRepositoryInstance()->findOrFail($req['id']);

            // delete the product super attributes info
            DB::table('product_super_attributes')->where('product_id', $req['id'])->delete();
            
            //$product->update($input);
            $id = $req['id'];

            

            //var_dump($input,$super_attributes_ids);exit;

            // add new super attributes
            foreach($super_attributes_ids as $key=>$super_attributes_id) {
                DB::table('product_super_attributes')->insert([
                    'product_id' => $id,
                    'attribute_id' => $super_attributes_id
                ]);
            }

           // exit;


        }else{
            Event::dispatch('catalog.product.create.before');
            $product = $this->getRepositoryInstance()->create($input);
            Event::dispatch('catalog.product.create.after', $product);
            $id = $product->id;
        }
        

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

        $Localvariants = $variantCollection = $product->variants()->get()->toArray();

        //var_dump($Localvariants);exit;

        $tableData = [];
        $skus = $request->input('tableData');

        $categories = $request->input('categories');
        // $categories = [];
        // foreach($categorie as $key=>$category) {
        //     $categories[] = $category;
        // }

        //var_dump($categories);exit;

        $Variants = [];
        $VariantsImages = [];
        $i = 0;
        foreach($skus as $key=>$sku) {
            $Variant = [];
            $sku['sku'] = !empty($sku['sku']) ? $sku['sku'] : $sku['label'];
            
            $Variant['name'] = $sku['label']; 
            $Variant['price'] = $sku['price'];
            $Variant['weight'] = "1000";
            $Variant['status'] = $req['status'];
            $Variant['inventories'][1] = 1000;
            $Variant['channel'] = Core()->getCurrentChannel()->code;
            $Variant['locale'] = Core()->getCurrentLocale()->code;
            $Variant['visible_individually'] = 1;

            $Variant['categories'] = $categories;
            $option1 = isset($super_attributes_label[1]) ? $super_attributes_label[1] : null;
            $option2 = isset($super_attributes_label[2]) ? $super_attributes_label[2] : null;
            $option3 = isset($super_attributes_label[3]) ? $super_attributes_label[3] : null;

            //var_dump($option1);
            //var_dump($option2);
            //var_dump($option3);

            if($option1) $Variant[$option1] = $sku['option1'];
            if($option2) $Variant[$option2] = $sku['option2'];
            if($option3) $Variant[$option3] = $sku['option3'];

            //var_dump($Variant);exit;
            
            if(empty($sku['id'])) {
                $Variant['sku'] = $input['sku'].'-'. $sku['sku'];
                $Variants["variant_".$i] = $Variant;
                $i++;
            }else{
                // use sku to find the variant
                


                $Variant['sku'] = $sku['sku'];
                $Variants[$sku['id']] = $Variant;
            }

            //add images to the variant
            $image = $sku['images'];


            

        }

        $tableData['channel'] = Core()->getCurrentChannel()->code;
        $tableData['locale'] = Core()->getCurrentLocale()->code;
        $tableData['variants'] = $Variants;
        $tableData['url_key'] = isset($req['url_key']) ? $req['url_key'] : $req['sku'];
        $tableData['name'] = $req['name'];
        $tableData['new'] = 1;
        $tableData['guest_checkout'] = 1;
        $tableData['status'] = $req['status'];
        $tableData['description'] = $req['description'];
        $tableData['price'] = $req['pricingData']['price'];
        $tableData['compare_at_price'] = $req['pricingData']['originalPrice'];
        $tableData['visible_individually'] = 1;
       // $tableData['categories'] = $categories;

       //var_dump($tableData);exit;

        $product = $this->getRepositoryInstance()->update($tableData, $id);

        Event::dispatch('catalog.product.update.after', $product);

        $images = $request->input('images');

        // add images to the product
        $productImages = [];
        foreach($images as $key=>$image) {
            $productImages[] = [
                'path' => $image['url'],
                'type' => 'images',
                'position' => $key
            ];
        }

        $product->images()->createMany($productImages);

        


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
