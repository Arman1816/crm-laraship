<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class AttributeSet extends BaseModel
{
    use PresentableTrait, LogsActivity;

    protected $table = 'marketplace_attribute_sets';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.attribute_set';


    protected $guarded = ['id'];

    protected $casts = [
        'properties' => 'json',
        'is_default' => 'boolean',
    ];


    public function productAttributes()
    {
        return $this->morphedByMany(Attribute::class,
            'model',
            'marketplace_set_has_models',
            'set_id');
    }

    public function scopeIsDefault($builder, $state = 1)
    {
        $builder->where('is_default', $state);
    }


    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
