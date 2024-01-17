<?php

namespace Corals\Modules\Marketplace\database\seeds;

use Corals\Modules\Subscriptions\Models\Product;
use Illuminate\Database\Seeder;

class MarketplaceSubscriptionProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->update(['status' => 'inactive']);

        $productId = \DB::table('products')->insertGetId([
            'name' => 'Marketplace Subscription Product',
            'description' => 'Marketplace subscription product for vendors',
            'status' => 'active',
            'deleted_at' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \DB::table('plans')->insert([
                [
                    'name' => 'Market Basic',
                    'code' => 'MARKET-BASIC',
                    'price' => '100',
                    'bill_frequency' => 1,
                    'trial_period' => 0,
                    'bill_cycle' => 'year',
                    'recommended' => 0,
                    'free_plan' => 0,
                    'display_order' => 1,
                    'description' => 'Basic vendor plan',
                    'product_id' => $productId,
                    'status' => 'active',
                    'deleted_at' => NULL,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Market Standard',
                    'code' => 'MARKET-STANDARD',
                    'price' => '150',
                    'bill_frequency' => 1,
                    'trial_period' => 0,
                    'bill_cycle' => 'year',
                    'recommended' => 1,
                    'free_plan' => 0,
                    'display_order' => 2,
                    'description' => 'Basic vendor plan',
                    'product_id' => $productId,
                    'status' => 'active',
                    'deleted_at' => NULL,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Market Extended',
                    'code' => 'MARKET-EXTENDED',
                    'price' => '300',
                    'bill_frequency' => 1,
                    'trial_period' => 0,
                    'bill_cycle' => 'year',
                    'recommended' => 0,
                    'free_plan' => 0,
                    'display_order' => 3,
                    'description' => 'Extended vendor plan',
                    'product_id' => $productId,
                    'status' => 'active',
                    'deleted_at' => NULL,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]
        );

        $commissionFeatureId = \DB::table('features')->insertGetId([
            'name' => 'Platform commission',
            'caption' => 'Platform commission',
            'description' => 'Platform commission',
            'product_id' => $productId,
            'status' => 'active',
            'type' => 'quantity',
            'display_order' => 1,
            'unit' => '%',
            'deleted_at' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $maximumProductsId = \DB::table('features')->insertGetId([
            'name' => 'Maximum Active Products',
            'caption' => 'Maximum Active Products',
            'description' => 'Maximum Active Products',
            'product_id' => $productId,
            'status' => 'active',
            'type' => 'quantity',
            'display_order' => 2,
            'unit' => 'Product(s)',
            'deleted_at' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $galleryImagesId = \DB::table('features')->insertGetId([
            'name' => 'Unlimited product gallery images',
            'caption' => 'Unlimited product gallery images',
            'description' => 'Unlimited product gallery images',
            'product_id' => $productId,
            'status' => 'active',
            'type' => 'boolean',
            'display_order' => 3,
            'unit' => null,
            'deleted_at' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $advertisementBenefitsId = \DB::table('features')->insertGetId([
            'name' => 'Opened to advertisement benefits',
            'caption' => 'Opened to advertisement benefits',
            'description' => 'Opened to advertisement benefits',
            'product_id' => $productId,
            'status' => 'active',
            'type' => 'boolean',
            'display_order' => 4,
            'unit' => null,
            'deleted_at' => NULL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $product = Product::find($productId);

        $loop = 1;

        foreach ($product->plans as $plan) {
            $commission = 2.5 * $loop++;
            $maxProducts = 50 * $loop++;

            \DB::table('feature_plan')->insert([
                [
                    'plan_id' => $plan->id,
                    'feature_id' => $commissionFeatureId,
                    'value' => $commission,
                ],
                [
                    'plan_id' => $plan->id,
                    'feature_id' => $maximumProductsId,
                    'value' => $maxProducts,
                ],
                [
                    'plan_id' => $plan->id,
                    'feature_id' => $galleryImagesId,
                    'value' => 1,
                ],
                [
                    'plan_id' => $plan->id,
                    'feature_id' => $advertisementBenefitsId,
                    'value' => 1,
                ],
            ]);
        }


        \Settings::set('marketplace_general_commission_feature', $commissionFeatureId);
        \Settings::set('marketplace_general_product_limit_feature', $maximumProductsId);
        \Settings::set('marketplace_general_subscription_product', $productId);
    }
}
