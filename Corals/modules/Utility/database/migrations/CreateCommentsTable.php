<?php

namespace Corals\Modules\Utility\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        \Schema::create('utility_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body');
            $table->string('status')->nullable();
            $table->morphs('commentable');
            $table->nullableMorphs('author');
            $table->boolean('is_private')->default(false);
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->text('properties')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        \Schema::dropIfExists('utility_comments');
    }
}
