<?php

namespace Corals\Modules\Subscriptions\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class FeatureModel extends BaseModel
{
    use PresentableTrait, LogsActivity;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'subscriptions.models.feature_model';

    protected static $logAttributes = [
        'feature_id', 'model'
    ];

    protected $guarded = ['id'];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
