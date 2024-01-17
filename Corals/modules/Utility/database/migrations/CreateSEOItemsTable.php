<?php

namespace Corals\Modules\Utility\database\migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSEOItemsTable extends Migration
{
    public function up()
    {
        \Schema::create('utilities_seo_items', function (Blueprint $table) {
            $table->increments('id');

            $table->string('route')->nullable()->unique()->index();
            $table->string('slug')->nullable()->unique()->index();
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->text('properties')->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        \Schema::dropIfExists('utilities_seo_items');
    }
}
