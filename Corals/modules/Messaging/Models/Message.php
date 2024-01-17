<?php

namespace Corals\Modules\Messaging\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\Messaging\Contracts\Message as MessageContract;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends BaseModel implements MessageContract, HasMedia
{
    use PresentableTrait, LogsActivity, InteractsWithMedia ;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'messaging.models.message';

    protected static $logAttributes = [];

    protected $fillable = ['discussion_id', 'participable_type', 'participable_id', 'body'];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['discussion'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'discussion_id' => 'integer',
        'participable_id' => 'integer',
    ];

    protected $table = 'messaging_messages';

    public function getModuleName()
    {
        return 'Messaging';
    }

    public $mediaCollectionName = 'message-files';

    /**
     * Participants relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getImageAttribute()
    {
        $media = $this->getFirstMedia($this->mediaCollectionName);

        if ($media) {
            return $media->getFullUrl();
        } else {
            return asset(config($this->config . '.default_image'));
        }
    }
    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------
     */

    /**
     * Discussion relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    /**
     * User/Author relationship (alias).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function author()
    {
        return $this->participable();
    }

    /**
     * Participable relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function participable()
    {
        return $this->morphTo();
    }

    /**
     * Participations relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations()
    {
        return $this->hasMany(Participation::class, 'discussion_id', 'discussion_id');
    }

    public function canDeleteMessage($discussion_id = 0)
    {
        $user = user();

        $mes = $this->hasMany(Participation::class, 'discussion_id', 'discussion_id')
            ->where("messaging_participations.participable_id", '!=', $user->getKey())
            ->where("messaging_participations.discussion_id", '=', $discussion_id)
            ->orderBy("messaging_participations.last_read", "desc")
            ->first();

        if ($mes && $this->created_at > $mes->last_read) {
            return true;
        } else {
            return false;
        }
    }
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Recipients of this message.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecipientsAttribute()
    {
        $morph = 'participable';

        return $this->participations->reject(function (Participation $participant) use ($morph) {
            return $participant->getAttribute("{$morph}_id") === $this->getAttribute("{$morph}_id")
                && $participant->getAttribute("{$morph}_type") === $this->getAttribute("{$morph}_type");
        });
    }
}
