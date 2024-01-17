<?php

namespace Corals\Modules\Utility\Models\Rating;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;

class AvgRating extends BaseModel
{
    use PresentableTrait;

    /**
     * @var string
     */
    protected $table = 'utility_avg_ratings';


    protected $casts = [
        'properties' => 'json',
        'criterias' => 'json'
    ];

    protected $guarded = ['id'];


    public function reviewrateable()
    {
        return $this->morphTo();
    }
}
