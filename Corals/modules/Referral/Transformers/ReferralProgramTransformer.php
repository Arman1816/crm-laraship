<?php

namespace Corals\Modules\Referral\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Referral\Models\ReferralProgram;

class ReferralProgramTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('referral_program.models.referral_program.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param ReferralProgram $referral_program
     * @return array
     * @throws \Throwable
     */
    public function transform(ReferralProgram $referral_program)
    {
        $show_url = $referral_program->getShowURL();

        $transformedArray = [
            'id' => $referral_program->id,
            'checkbox' => $this->generateCheckboxElement($referral_program),
            'name' => '<a href="' . $show_url . '">' . \Str::limit($referral_program->name, 50) . '</a>',
            'referral_action' => ucfirst($referral_program->referral_action),
            'title' => $referral_program->title,
            'uri' => '<a target="_blank" href="' . url($referral_program->uri) . '">' . $referral_program->uri . '</a>',
            'status' => formatStatusAsLabels($referral_program->status),
            'created_at' => format_date($referral_program->created_at),
            'updated_at' => format_date($referral_program->updated_at),
            'short_code' => $this->getShortcode($referral_program),
            'action' => $this->actions($referral_program)
        ];

        return parent::transformResponse($transformedArray);
    }

    protected function getShortcode($referral_program)
    {
        return '<b id="shortcode_' . $referral_program->id . '">@referral_program(' . $referral_program->key . ')</b> 
                <a href="#" onclick="event.preventDefault();" class="copy-button"
                data-clipboard-target="#shortcode_' . $referral_program->id . '"><i class="fa fa-clipboard"></i></a>';
    }
}
