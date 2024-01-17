<?php

use Corals\Modules\Payment\Common\Models\Transaction;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!\Schema::hasColumn('payment_transactions', 'code')) {


    Schema::table('payment_transactions', function (Blueprint $table) {
        $table->string('code')->nullable()->after('id');
    });

    Transaction::query()->each(function ($transaction) {
        $transaction->update(['code' => Transaction::getCode('TR')]);
    });

}
