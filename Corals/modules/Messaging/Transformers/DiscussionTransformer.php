<?php

namespace Corals\Modules\Messaging\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Messaging\Models\Discussion;

class DiscussionTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('messaging.models.discussion.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Discussion $discussion
     * @return array
     * @throws \Throwable
     */
    public function transform(Discussion $discussion)
    {
        $show_url = url($this->resource_url . '/' . $discussion->hashed_id);

        $levels = [
            'read' => 'success',
            'unread' => 'info',
            'deleted' => 'danger',
            'important' => 'primary',
            'star' => 'warning',
        ];

        $userParticipation = $discussion->getUserParticipation();

        $status = null;

        if ($userParticipation) {
            $status = $userParticipation->status;
        }


        $participations = [];

        foreach ($discussion->participations as $participation) {
            $participable = $participation->participable;
            if ($participable) {
                $participations[] = '<img src="' . $participable->picture_thumb . '" width="20" height="20">&nbsp;' . $participable->name;

            }
        }

        $transformedArray = [
            'id' => $discussion->id,
            'checkbox' => $this->addCheckbox($discussion->hashed_id),
            'icon' => $this->addIcon($status),
            'creator' => $discussion->creator() ? '<b>' . $discussion->creator()->name . '</b>' : '',
            'subject' => '<a href="' . $show_url . '">' . \Str::limit($discussion->subject, 50) . '</a>',
            'participations' => formatArrayAsLabels($participations, 'info'),
            'created_at' => format_date($discussion->created_at),
            'updated_at' => format_date($discussion->updated_at),
            'action' => $this->actions($discussion),
        ];

        return parent::transformResponse($transformedArray);
    }

    public function addCheckbox($hashedId = null)
    {
        $checkbox = '<div class="custom-control custom-checkbox"><input type="checkbox" name="checkbox[]" value="' . $hashedId . '" class="checkbox custom-control-input" id="' . $hashedId . '"/><label class="custom-control-label" for="' . $hashedId . '"> </label></div>';

        return $checkbox;
    }

    public function addIcon($status = null)
    {

        $icon = '';

        if (is_null($status)) {
            return '';
        }

        if ($status == 'read') {
            $icon = '<i class="fa fa-envelope-open">';
        } else if ($status == 'unread') {
            $icon = '<i class="fa fa-envelope">';
        } else if ($status == 'important') {
            $icon = '<i class="fa fa-info-circle">';
        } else if ($status == 'deleted') {
            $icon = '<i class="fa fa-trash-o">';
        } else if ($status == 'star') {
            $icon = '<i class="fa fa-star">';
        }

        return $icon;
    }
}
