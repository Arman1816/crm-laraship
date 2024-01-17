<?php

namespace Corals\Modules\Utility\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utility_guides', function (Blueprint $table) {
            $table->increments('id');

            $table->string('url')->unique()->nullable();
            $table->string('route')->unique()->nullable();

            $table->text('properties')->nullable();


            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->softDeletes();
            $table->auditable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility_guides');
    }
}
