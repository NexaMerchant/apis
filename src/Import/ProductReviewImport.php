<?php

namespace NexaMerchant\Apis\Import;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductReview;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductReviewImport implements ToModel, WithHeadingRow, WithValidation {
    public function model(array $row)
    {
        // Find the product by SKU
        $product = Product::where('sku', $row['product_sku'])->first();

        if (!$product) {
            // Skip if product not found
            return null;
        }

        // check the customer email have create a customer or not
        $customer = DB::table('customers')->where('email', $row['customer_email'])->first();
        if(!$customer) {
            // create a new customer
            $customer = DB::table('customers')->insertGetId([
                'first_name' => $row['customer_name'],
                'last_name' => $row['customer_name'],
                'email' => $row['customer_email'],
                'password' => bcrypt('password'),
                'channel_id' => 1,
                'is_verified' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }



        // Create a new Product Review
        $productReview = new ProductReview([
            'product_id'    => $product->id,
            'customer_id'   => $customer->id,
            'name' => $row['customer_name'],
            'title'         => $row['title'],
            'rating'        => $row['rating'],
            'comment'       => $row['comment'] ?? '',
            'status'        => 'pending', // default pending
            'sort'          => 0,
        ]);

        // if the images are provided and add images url to the product review attachments
        if (isset($row['images'])) {
            $images = explode(',', $row['images']);
            foreach ($images as $image) {
                $productReview->attachments()->create([
                    'path' => $image,
                    'type' => 'image',
                    'mime_type' => 'jpeg',
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.product_sku'   => 'required|string|exists:products,sku',
            '*.customer_name' => 'required|string',
            '*.customer_email' => 'required|email',
            '*.title'         => 'required|string',
            '*.rating'        => 'required|integer|min:1|max:5',
            '*.comment'       => 'nullable|string',
            '*.images'       => 'nullable|string',
            '*.status'        => 'nullable|boolean',
        ];
    }
}