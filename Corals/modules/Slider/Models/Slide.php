<?php

namespace Corals\Modules\Slider\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Slide extends BaseModel implements HasMedia
{
    use PresentableTrait, LogsActivity, InteractsWithMedia ;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'slider.models.slide';

    protected static $logAttributes = ['name'];

    protected $guarded = ['id'];

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }
}
