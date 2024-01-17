<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\GatewayStatusTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Tag extends BaseModel
{
    use PresentableTrait, LogsActivity, GatewayStatusTrait;

    protected $table = 'marketplace_tags';

    protected $casts = [
        'properties' => 'json',
    ];
    
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.tag';

    protected static $logAttributes = ['name'];

    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'marketplace_product_tag');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Str::slug($value);
    }
}
