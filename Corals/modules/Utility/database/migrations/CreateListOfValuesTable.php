<?php

namespace Corals\Modules\Utility\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListOfValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utility_list_of_values', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code')->unique();
            $table->string('label')->nullable();
            $table->text('value');
            $table->unsignedInteger('parent_id')->nullable();
            $table->text('properties')->nullable();
            $table->string('module')->nullable();
            $table->integer('display_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('hidden')->default(false);
            
            $table->softDeletes();
            $table->auditable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('utility_list_of_values')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utility_list_of_values');
    }
}
