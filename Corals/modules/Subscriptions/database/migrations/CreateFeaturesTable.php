<?php

namespace Corals\Modules\Subscriptions\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('caption');
            $table->text('description')->nullable();
            $table->unsignedInteger('product_id');
            $table->enum('status', ['active', 'inactive', 'deleted'])->default('active');
            $table->enum('type', ['quantity', 'config', 'boolean', 'text'])->default('boolean');
            $table->unsignedInteger('display_order')->default(0);
            $table->text('unit')->nullable();
            $table->text('related_urls')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->boolean('per_cycle')->default(false);

            $table->string('limit_reached_message')->nullable();

            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
    }
}
