<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Traits\Node\SimpleNode;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class AttributeOption extends BaseModel implements HasMedia
{
    use PresentableTrait, LogsActivity, SimpleNode, InteractsWithMedia ;

    public $timestamps = false;

    protected $table = 'marketplace_attribute_options';

    public $galleryMediaCollection = 'marketplace-attribute-option';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.attribute_option';


    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsToMany(Attribute::class);
    }

}
