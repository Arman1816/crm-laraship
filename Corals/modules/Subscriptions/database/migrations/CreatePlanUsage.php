<?php

namespace Corals\Modules\Subscriptions\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanUsage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_usage', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('subscription_id');
            $table->unsignedInteger('feature_id');
            $table->unsignedInteger('cycle_id')->nullable();

            $table->text('usage_details');

            $table->text('properties')->nullable();

            $table->timestamps();
            $table->auditable();

            $table->foreign('feature_id')
                ->references('id')->on('features')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('subscription_id')
                ->references('id')->on('subscriptions')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('cycle_id')
                ->references('id')->on('subscription_cycles')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('feature_models', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('feature_id');
            $table->string('model')->index();

            $table->timestamps();
            $table->auditable();

            $table->foreign('feature_id')
                ->references('id')->on('features')
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
        Schema::dropIfExists('feature_models');
        Schema::dropIfExists('plan_usage');
    }
}
