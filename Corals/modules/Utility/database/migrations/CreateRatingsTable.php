<?php

namespace Corals\Modules\Utility\database\migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    public function up()
    {
        if (!schemaHasTable('utility_ratings')) {
            \Schema::create('utility_ratings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('rating');
                $table->string('title')->nullable();
                $table->string('body')->nullable();
                $table->morphs('reviewrateable');
                $table->morphs('author');
                $table->text('properties')->nullable();
                $table->string('criteria')->nullable();
                $table->enum('status', ['approved', 'disapproved', 'spam', 'pending'])->default('approved');
                $table->unsignedInteger('created_by')->nullable()->index();
                $table->unsignedInteger('updated_by')->nullable()->index();

                $table->softDeletes();
                $table->timestamps();
            });


            \Schema::create('utility_avg_ratings', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('count');
                $table->decimal('avg');

                $table->morphs('avgreviewable');

                $table->text('criterias')->nullable();
                $table->text('properties')->nullable();

                $table->unsignedInteger('created_by')->nullable()->index();
                $table->unsignedInteger('updated_by')->nullable()->index();

                $table->softDeletes();
                $table->timestamps();
            });

        }
    }

    public function down()
    {
        \Schema::dropIfExists('utility_ratings');
        \Schema::dropIfExists('utility_avg_ratings');
    }
}
