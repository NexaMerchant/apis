<?php

namespace NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\Promotions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Repositories\CartRuleRepository;
use NexaMerchant\Apis\Http\Controllers\Api\V1\Admin\Marketing\MarketingController;
use NexaMerchant\Apis\Http\Resources\Api\V1\Admin\Marketing\Promotions\CartRuleResource;

class CartRuleController extends MarketingController
{
    /**
     * Repository class name.
     */
    public function repository(): string
    {
        return CartRuleRepository::class;
    }

    /**
     * Resource class name.
     */
    public function resource(): string
    {
        return CartRuleResource::class;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'coupon_code'         => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',
        ]);

        Event::dispatch('promotions.cart_rule.create.before');

        $cartRule = $this->getRepositoryInstance()->create($request->all());

        Event::dispatch('promotions.cart_rule.create.after', $cartRule);

        return response([
            'data'    => new CartRuleResource($cartRule),
            'message' => trans('Apis::app.admin.marketing.promotions.cart-rules.create-success'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',
        ]);

        $cartRule = $this->getRepositoryInstance()->findOrFail($id);

        if ($cartRule->coupon_type) {
            if ($cartRule->cart_rule_coupon) {
                $request->validate([
                    'coupon_code' => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code,'.$cartRule->cart_rule_coupon->id,
                ]);
            } else {
                $request->validate([
                    'coupon_code' => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code',
                ]);
            }
        }

        Event::dispatch('promotions.cart_rule.update.before', $id);

        $cartRule = $this->getRepositoryInstance()->update($request->all(), $id);

        Event::dispatch('promotions.cart_rule.update.after', $cartRule);

        return response([
            'data'    => new CartRuleResource($cartRule),
            'message' => trans('Apis::app.admin.marketing.promotions.cart-rules.update-success'),
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

        Event::dispatch('promotions.cart_rule.delete.before', $id);

        $this->getRepositoryInstance()->delete($id);

        Event::dispatch('promotions.cart_rule.delete.after', $id);

        return response([
            'message' => trans('Apis::app.admin.marketing.promotions.cart-rules.delete-success'),
        ]);
    }

    /**
     *  getConditionAttributes
     * 
     */
    public function getConditionAttributes() {

        $cartRule = app(\Webkul\CartRule\Repositories\CartRuleRepository::class);
       // $categories = app(\Webkul\Category\Repositories\CategoryRepository::class)->getCategoryTree();
       // $categoryRepository = app(\Webkul\Category\Repositories\CategoryRepository::class);
        $attributeRepository = app(\Webkul\Attribute\Repositories\AttributeRepository::class);
        
        $attributes = [
            [
                'key'      => 'cart',
                'label'    => trans('admin::app.marketing.promotions.cart-rules.create.cart-attribute'),
                'children' => [
                    [
                        'key'   => 'cart|base_sub_total',
                        'type'  => 'price',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.subtotal'),
                    ], [
                        'key'   => 'cart|items_qty',
                        'type'  => 'integer',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.total-items-qty'),
                    ], [
                        'key'     => 'cart|payment_method',
                        'type'    => 'select',
                        'options' => $cartRule->getPaymentMethods(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.payment-method'),
                    ], [
                        'key'     => 'cart|shipping_method',
                        'type'    => 'select',
                        'options' => $cartRule->getShippingMethods(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.shipping-method'),
                    ], [
                        'key'   => 'cart|postcode',
                        'type'  => 'text',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.shipping-postcode'),
                    ], [
                        'key'     => 'cart|state',
                        'type'    => 'select',
                        'options' => $cartRule->groupedStatesByCountries(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.shipping-state'),
                    ], [
                        'key'     => 'cart|country',
                        'type'    => 'select',
                        'options' => $cartRule->getCountries(),
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.shipping-country'),
                    ],
                ],
            ], [
                'key'      => 'cart_item',
                'label'    => trans('admin::app.marketing.promotions.cart-rules.create.cart-item-attribute'),
                'children' => [
                    [
                        'key'   => 'cart_item|base_price',
                        'type'  => 'price',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.price-in-cart'),
                    ], [
                        'key'   => 'cart_item|quantity',
                        'type'  => 'integer',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.qty-in-cart'),
                    ], [
                        'key'   => 'cart_item|base_total_weight',
                        'type'  => 'decimal',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.total-weight'),
                    ], [
                        'key'   => 'cart_item|base_total',
                        'type'  => 'price',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.subtotal'),
                    ], [
                        'key'   => 'cart_item|additional',
                        'type'  => 'text',
                        'label' => trans('admin::app.marketing.promotions.cart-rules.create.additional'),
                    ],
                ],
            ], [
                'key'      => 'product',
                'label'    => trans('admin::app.marketing.promotions.cart-rules.create.product-attribute'),
                'children' => [
                    [
                        'key'     => 'product|category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.categories'),
                        'options' => [],
                    ], [
                        'key'     => 'product|children::category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.children-categories'),
                        'options' => [],
                    ], [
                        'key'     => 'product|parent::category_ids',
                        'type'    => 'multiselect',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.parent-categories'),
                        'options' => [],
                    ], [
                        'key'     => 'product|attribute_family_id',
                        'type'    => 'select',
                        'label'   => trans('admin::app.marketing.promotions.cart-rules.create.attribute-family'),
                        'options' => $cartRule->getAttributeFamilies(),
                    ],
                ],
            ],
        ];

        $tempAttributes = $attributeRepository->with([
            'translations',
            'options',
            'options.translations'
        ])->findWhereNotIn('type', [
            'textarea',
            'image',
            'file'
        ]);

        //return $tempAttributes;

        foreach ($tempAttributes as $attribute) {
            $attributeType = $attribute->type;

            if ($attribute->code == 'tax_category_id') {
                $options = $cartRule->getTaxCategories();
            } else {
                $options = $attribute->options;
            }

            if ($attribute->validation == 'decimal') {
                $attributeType = 'decimal';
            } elseif ($attribute->validation == 'numeric') {
                $attributeType = 'integer';
            }

            $attributes[2]['children'][] = [
                'key'     => 'product|' . $attribute->code,
                'type'    => $attribute->type,
                'label'   => $attribute->name,
                'options' => $options,
            ];

            $attributes[2]['children'][] = [
                'key'     => 'product|children::' . $attribute->code,
                'type'    => $attribute->type,
                'label'   => trans('admin::app.marketing.promotions.cart-rules.create.attribute-name-children-only', ['attribute_name' => $attribute->name]),
                'options' => $options,
            ];

            $attributes[2]['children'][] = [
                'key'     => 'product|parent::' . $attribute->code,
                'type'    => $attribute->type,
                'label'   => trans('admin::app.marketing.promotions.cart-rules.create.attribute-name-parent-only', ['attribute_name' => $attribute->name]),
                'options' => $options,
            ];
        }

        //return $attributes;



        return response()->json([
           'data' => $attributes
        ]);
    }

    /**
     * 
     * Create a new cart rule for the product quantity
     * 
     * @param int $product_id
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function createProductQuantityRule($product_id, Request $request){
        $product = app(\Webkul\Product\Repositories\ProductRepository::class)->findOrFail($product_id);
        if(!$product){
            return response()->json([
                'message' => trans('admin::app.marketing.promotions.cart-rules.product-not-found')
            ], 404);
        }

        $request->set('product_id', $product_id);
        $request->set('name', $product->name);
        $request->set('channels', ['all']);
        $request->set('customer_groups', ['all']);
        $request->set('coupon_type', 0);
        

        // create a new cart rule for the product quantity
        $request->validate([
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'coupon_code'         => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',
        ]);

    }

}
