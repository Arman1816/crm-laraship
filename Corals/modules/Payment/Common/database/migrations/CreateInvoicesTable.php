<?php

namespace Corals\Modules\Payment\Common\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('reference_id')->nullable();
            $table->string('currency');
            $table->text('description')->nullable();
            $table->text('terms')->nullable();
            $table->enum('status', ['paid', 'pending', 'failed', 'cancelled'])->default('pending');
            $table->dateTime('due_date');
            $table->dateTime('invoice_date');
            $table->decimal('sub_total');
            $table->decimal('total');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('invoicable_type');
            $table->unsignedInteger('invoicable_id');
            $table->boolean('is_sent')->nullable()->default(false);
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->text('properties')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->decimal('amount');
            $table->integer('quantity')->nullable()->default(1);
            $table->string('itemable_type');
            $table->unsignedInteger('itemable_id');
            $table->string('object_reference')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('invoice_id');

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();


            $table->foreign('invoice_id')->references('id')
                ->on('invoices')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
}
