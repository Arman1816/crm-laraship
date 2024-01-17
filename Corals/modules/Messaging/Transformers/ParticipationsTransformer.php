<?php

namespace Corals\Modules\Messaging\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Messaging\Models\Participation;

class ParticipationsTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('messaging.models.participation.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Participation $participation
     * @return array
     * @throws \Throwable
     */
    public function transform(Participation $participation)
    {
        $show_url = url($this->resource_url . '/' . $participation->hashed_id);

        $transformedArray = [
            'id' => $participation->id,
            'name' => '<img src="' . $participation->participable->picture_thumb . '" width="20" height="20">&nbsp;' . $participation->participable->name,
            'created_at' => format_date($participation->created_at),
            'updated_at' => format_date($participation->updated_at),
        ];

        return parent::transformResponse($transformedArray);
    }
}
