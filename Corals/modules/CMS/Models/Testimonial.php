<?php

namespace Corals\Modules\CMS\Models;

use Corals\Foundation\Traits\ModelPropertiesTrait;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;

class Testimonial extends Content
{
    use Sluggable, ModelPropertiesTrait;

    public function getModuleName()
    {
        return 'CMS';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'testimonial');
        });
    }

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'cms.models.testimonial';

    public $mediaCollectionName = 'testimonial-author-image';

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia($this->mediaCollectionName);

        if ($media) {
            return $media->getFullUrl();
        } else {

            return asset(config($this->config . '.default_image'));
        }
    }

    protected $table = 'posts';

    protected $attributes = [
        'type' => 'testimonial'
    ];

    protected $casts = [
        'properties' => 'json',
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true
            ]
        ];
    }

    protected $fillable = ['title', 'content', 'published', 'published_at', 'type', 'properties', 'author_id'];


}
