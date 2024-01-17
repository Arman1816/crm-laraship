<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends BaseModel
{
    use PresentableTrait, LogsActivity;

    protected $table = 'marketplace_shipping_packages';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.package';

    protected static $logAttributes = [];

    protected $casts = [
        'properties' => 'json'
    ];

    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
