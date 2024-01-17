<?php

namespace Corals\Modules\Utility\Models\Guide;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;

class Guide extends BaseModel
{
    use PresentableTrait, LogsActivity, ModelPropertiesTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'utility.models.guide';

    protected $table = 'utility_guides';

    protected static $logAttributes = ['name'];

    protected $casts = [
        'properties' => 'json',
    ];

    public $guarded = ['id'];
}
