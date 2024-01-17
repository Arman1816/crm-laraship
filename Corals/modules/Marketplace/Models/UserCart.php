<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\User\Models\User;

class UserCart extends BaseModel
{
    protected $table = 'marketplace_user_cart';

    protected $guarded = ['id'];

    protected $casts = [
        'abandoned_email_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
