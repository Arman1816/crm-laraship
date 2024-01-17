<?php

use Corals\Modules\Subscriptions\database\migrations\CreatePlanUsage;
use Corals\Modules\Subscriptions\database\migrations\CreateSubscriptionCycles;

\Illuminate\Support\Facades\Schema::table('feature_plan', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->text('plan_caption')->after('value');
});

\Illuminate\Support\Facades\Schema::table('features', function (\Illuminate\Database\Schema\Blueprint $table) {
    $table->boolean('is_visible')->default(true)->after('unit');
    $table->boolean('per_cycle')->default(false)->after('unit');
    $table->text('related_urls')->nullable()->after('unit');
    $table->string('limit_reached_message')->nullable()->after('unit');
});

\DB::statement("ALTER TABLE `features` 
MODIFY COLUMN `type` enum('quantity','boolean','text','config') 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'boolean' AFTER `extras`;");

(new CreateSubscriptionCycles())->up();
(new CreatePlanUsage())->up();
