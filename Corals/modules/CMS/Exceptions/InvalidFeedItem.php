<?php

namespace Corals\Modules\CMS\Exceptions;

use Exception;
use Corals\Modules\CMS\Classes\Feed\FeedItem;

class InvalidFeedItem extends Exception
{
    /** @var \Corals\Modules\CMS\Classes\Feed\FeedItem|null */
    public $subject;

    public static function notFeedable($subject)
    {
        return (new static('Object doesn\'t implement `Corals\Modules\CMS\Classes\Feed\Feedable`'))->withSubject($subject);
    }

    public static function notAFeedItem($subject)
    {
        return (new static('`toFeedItem` should return an instance of `Corals\Modules\CMS\Classes\Feed\Feedable`'))->withSubject($subject);
    }

    public static function missingField(FeedItem $subject, $field)
    {
        return (new static("Field `{$field}` is required"))->withSubject($subject);
    }

    protected function withSubject()
    {
        $this->subject;

        return $this;
    }
}
