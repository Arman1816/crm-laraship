<?php

namespace Corals\Modules\Utility\Transformers\Rating;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Utility\Models\Rating\Rating;

class RatingTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('utility.models.rating.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Rating $rating
     * @return array
     * @throws \Throwable
     */
    public function transform(Rating $rating)
    {
        $showURL = optional($rating->reviewrateable)->getShowURL();

        $levels = [
            'pending' => 'info',
            'approved' => 'success',
            'disapproved' => 'danger',
            'spam' => 'warning'
        ];

        $setting_name = strtolower(class_basename($rating->reviewrateable)) . '_default_rating_status';

        $status = $rating->status ? $rating->status : \Settings::get($setting_name);

        $transformedArray = [
            'id' => $rating->id,
            'checkbox' => $this->generateCheckboxElement($rating),
            'rating' => \RatingManager::drawStarts($rating->rating),
            'title' => (strlen($rating->title) <= 30) ? $rating->title : \Str::limit($rating->title, 30, generatePopover($rating->title)),
            'body' => $rating->body ? generatePopover($rating->body) : '-',
            'reviewrateable_id' => $rating->reviewrateable ? '<a href="' . $showURL . '"target="_blank">' . $rating->reviewrateable->getDisplayReference() . '</a>' : '-',
            'reviewrateable_type' => $rating->reviewrateable ? class_basename($rating->reviewrateable_type) : '-',
            'author_id' => $rating->author ? "<a href='" . url('users/' . $rating->author->hashed_id) . "'> {$rating->author->full_name}</a>" : "-",
            'status' => formatStatusAsLabels($status, ['level' => $levels[$status], 'text' => trans('Utility::attributes.rating.status_options.' . $status)]),
            'comments_count' => $rating->comments->count() ? "<a href='" . url('utilities/comments?commentable_id=' . $rating->id . '&commentable_type=' . get_class($rating)) . "'>{$rating->comments->count()}</a>" : '-',
            'created_at' => format_date($rating->created_at),

            'action' => $this->actions($rating),
        ];

        return parent::transformResponse($transformedArray);
    }
}
