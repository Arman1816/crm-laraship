<?php

namespace Corals\Modules\Marketplace\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketplaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('code')->nullable()->index();
            $table->text('short_description')->nullable();
            $table->text('return_policy')->nullable();
            $table->nullableMorphs('causer');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('slug')->unique()->index();

            $table->longText('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('parking_domain')->index()->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedSmallInteger('custom_commission')->nullable();
            $table->boolean('is_featured')->default(false);

            $table->text('properties')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('marketplace_brands', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique();
            $table->string('slug')->unique()->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('store_id')->nullable()->index();

            $table->boolean('is_featured')->default(false);
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('marketplace_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->integer('total_sales')->default(0)->index();
            $table->string('product_code')->nullable()->unique()->index();
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            $table->text('price_per_quantity')->nullable();
            $table->text('offers')->nullable();
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->unsignedInteger('brand_id')->nullable()->index();
            $table->text('properties')->nullable();
            $table->integer('visitors_count')->default(0);
            $table->string('classification_price')->nullable();

            $table->text('shipping')->nullable();
            $table->text('caption')->nullable();
            $table->string('code')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('demo_url')->nullable();
            $table->text('external_url')->nullable();
            $table->unsignedInteger('store_id')->nullable()->index();


            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('marketplace_brands')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('marketplace_sku', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('regular_price');
            $table->decimal('sale_price')->nullable();
            $table->string('code');
            $table->enum('inventory', ['finite', 'bucket', 'infinite'])->default('infinite')->nullable();
            $table->string('inventory_value')->nullable();
            $table->unsignedInteger('product_id')->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('shipping')->nullable();
            $table->integer('allowed_quantity')->default(0);
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('marketplace_products')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('marketplace_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->unique();
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->string('external_id')->nullable();
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('marketplace_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('code')->unique()->index();
            $table->string('label');
            $table->integer('display_order')->default(0);
            $table->unsignedInteger('store_id')->nullable()->index();

            $table->boolean('required')->default(false);
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');

            $table->softDeletes();
            $table->timestamps();
        });


        Schema::create('marketplace_category_attributes', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('attribute_id')->index();
            $table->unsignedInteger('category_id')->index();

            $table->foreign('attribute_id')
                ->references('id')->on('marketplace_attributes')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('category_id')->references('id')
                ->on('marketplace_categories')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('marketplace_product_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attribute_id')->index();
            $table->unsignedInteger('product_id')->index();
            $table->boolean('sku_level')->default(false);
            $table->foreign('attribute_id')->references('id')->on('marketplace_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('marketplace_attribute_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_id')->unsigned()->index();
            $table->integer('option_order');
            $table->string('option_value');
            $table->string('option_display');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->softDeletes();

            $table->foreign('attribute_id')->references('id')->on('marketplace_attributes')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('marketplace_sku_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sku_id')->nullable()->index();
            $table->unsignedInteger('product_id')->nullable()->index();
            $table->integer('attribute_id')->unsigned()->index()->nullable();

            $table->unsignedInteger('attribute_option_id')->nullable()->index();

            $table->string('string_value', 255)->nullable();
            $table->double('number_value')->nullable();
            $table->text('text_value')->nullable();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->foreign('attribute_id')->references('id')->on('marketplace_attributes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('attribute_option_id')->references('id')->on('marketplace_attribute_options')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('marketplace_sku')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('marketplace_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->unique()->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('marketplace_category_product', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('marketplace_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['product_id', 'category_id']);
        });

        Schema::create('marketplace_product_tag', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('tag_id');
            $table->foreign('tag_id')->references('id')->on('marketplace_tags')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['product_id', 'tag_id']);
        });

        //Coupons
        Schema::create('marketplace_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id')->nullable()->index();
            $table->string('code')->unique('code');
            $table->enum('type', ['fixed', 'percentage'])->default('fixed');
            $table->integer('uses')->nullable();
            $table->decimal('min_cart_total')->nullable();
            $table->decimal('max_discount_value')->nullable();
            $table->string('value');
            $table->dateTime('start')->nullable();
            $table->dateTime('expiry')->nullable();
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
        });

        //Shipping
        Schema::create('marketplace_shippings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('store_id')->nullable()->index();
            $table->string('name');
            $table->integer('priority');
            $table->string('shipping_method');
            $table->decimal('rate')->default(0.0);
            $table->decimal('min_order_total')->default(0.0);
            $table->decimal('max_total_weight')->nullable();
            $table->integer('min_total_quantity')->nullable();
            $table->boolean('exclusive')->default(false);
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->text('properties')->nullable();
            $table->unsignedInteger('product_id')->nullable()->index();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('marketplace_shipping_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id')->nullable()->index();
            $table->string('name');
            $table->string('dimension_template')->nullable();
            $table->decimal('length', 10, 4)->nullable();
            $table->decimal('width', 10, 4)->nullable();
            $table->decimal('height', 10, 4)->nullable();
            $table->string('distance_unit')->nullable();

            $table->decimal('weight', 10, 4)->nullable();
            $table->string('mass_unit')->nullable();

            $table->string('integration_id')->nullable();

            $table->text('description')->nullable();
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('marketplace_coupon_product', function (Blueprint $table) {
            $table->integer('coupon_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('marketplace_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('marketplace_coupons')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('marketplace_coupon_user', function (Blueprint $table) {
            $table->integer('coupon_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('marketplace_coupons')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('marketplace_user_cart', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('instance_id')->index();
            $table->text('cart')->nullable();
            $table->boolean('abandoned_email_sent')->default(false);

            $table->timestamps();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });


        Schema::create('marketplace_attribute_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('store_id')->nullable()->index();

            $table->longText('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->foreign('store_id')->references('id')->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('marketplace_set_has_models', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('set_id');
            $table->unsignedInteger('model_id');
            $table->string('model_type');

            $table->longText('properties')->nullable();

            $table->timestamps();

            $table->foreign('set_id')
                ->references('id')
                ->on('marketplace_attribute_sets')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace_set_has_models');
        Schema::dropIfExists('marketplace_attribute_sets');
        Schema::dropIfExists('marketplace_user_cart');
        Schema::dropIfExists('marketplace_coupon_product');
        Schema::dropIfExists('marketplace_coupon_user');
        Schema::dropIfExists('marketplace_coupons');
        Schema::dropIfExists('marketplace_shippings');
        Schema::dropIfExists('marketplace_product_tag');
        Schema::dropIfExists('marketplace_category_product');
        Schema::dropIfExists('marketplace_category_attributes');
        Schema::dropIfExists('marketplace_tags');
        Schema::dropIfExists('marketplace_categories');
        Schema::dropIfExists('marketplace_sku_options');
        Schema::dropIfExists('marketplace_sku');
        Schema::dropIfExists('marketplace_attribute_options');
        Schema::dropIfExists('marketplace_product_attributes');
        Schema::dropIfExists('marketplace_attributes');
        Schema::dropIfExists('marketplace_wishlists');
        Schema::dropIfExists('marketplace_products');
        Schema::dropIfExists('marketplace_brands');
        Schema::dropIfExists('marketplace_stores');
    }
}
