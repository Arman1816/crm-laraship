<?php

namespace Corals\Modules\Utility\Models\ListOfValue;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\Cache\Cachable;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Traits\ModelUniqueCode;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class ListOfValue extends BaseModel
{
    use PresentableTrait, LogsActivity, ModelPropertiesTrait, ModelUniqueCode, Cachable;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'utility.models.listOfValue';

    protected $table = 'utility_list_of_values';

    protected static $logAttributes = ['code', 'value', 'properties', 'parent_id'];

    protected $casts = [
        'properties' => 'json',
    ];

    public $guarded = ['id'];

    public function scopeWithModule(Builder $query, string $module = null): Builder
    {
        if (is_null($module)) {
            return $query;
        }

        return $query->where('module', $module);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function parent()
    {
        return $this->belongsTo(ListOfValue::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(ListOfValue::class, 'parent_id', 'id');
    }

    public function getLabelAttribute()
    {
        if (empty($this->attributes['label'])) {
            return data_get($this->attributes, 'value');
        } else {
            return data_get($this->attributes, 'label');
        }
    }
}
