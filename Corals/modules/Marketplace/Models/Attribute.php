<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\GatewayStatusTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Attribute extends BaseModel
{
    use PresentableTrait, LogsActivity, GatewayStatusTrait;

    protected $table = 'marketplace_attributes';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.attribute';


    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'marketplace_category_attributes');
    }

    public function attributeSets()
    {
        return $this->morphToMany(AttributeSet::class, 'model', 'marketplace_set_has_models', 'model_id', 'set_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
