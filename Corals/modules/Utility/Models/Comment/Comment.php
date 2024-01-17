<?php

namespace Corals\Modules\Utility\Models\Comment;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Utility\Traits\Comment\ModelHasComments;

class Comment extends BaseModel
{

    use PresentableTrait, ModelHasComments;

    public static function htmlentitiesExcluded($key = null)
    {
        return false;
    }

    /**
     * @var string
     */
    protected $table = 'utility_comments';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'utility.models.comment';
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'properties' => 'json',
        'is_private' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function author()
    {
        return $this->morphTo();
    }

    public function scopeComments($query, $commentable_id, $commentable_type)
    {
        return $query->where('commentable_id', $commentable_id)->where('commentable_type', $commentable_type);
    }

    public function getIdentifier($key = null)
    {
        return 'Comment';
    }
}
