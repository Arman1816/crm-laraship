<?php

namespace Corals\Modules\Marketplace\database\migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->decimal('amount');
            $table->string('currency');
            $table->string('status');
            $table->text('shipping')->nullable();
            $table->text('billing')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('store_id')->references('id')
                ->on('marketplace_stores')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('marketplace_order_items', function (Blueprint $table) {

            $table->increments('id');
            $table->decimal('amount');
            $table->text('description')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('sku_code')->nullable();
            $table->string('type');
            $table->text('item_options')->nullable();

            $table->unsignedInteger('order_id');
            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();


            $table->foreign('order_id')->references('id')
                ->on('marketplace_orders')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace_order_items');
        Schema::dropIfExists('marketplace_orders');
    }
}
