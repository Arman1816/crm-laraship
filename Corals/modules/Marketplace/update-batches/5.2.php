<?php

use Corals\User\Communication\Models\NotificationTemplate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


Schema::table('marketplace_stores', function (Blueprint $table) {
    $table->unsignedSmallInteger('custom_commission')->nullable()->after('user_id');

});

\Corals\Modules\Payment\Common\Models\Transaction::where('type','commision')->update(['type'=>'commission']);

