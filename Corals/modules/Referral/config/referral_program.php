<?php

return [
    'models' => [
        'referral_program' => [
            'presenter' => \Corals\Modules\Referral\Transformers\ReferralProgramPresenter::class,
            'resource_url' => 'referral/referral-programs',
            'actions' => [
                'referral_links' => [
                    'icon' => 'fa fa-link',
                    'href_pattern' => ['pattern' => '[arg]/referral-relationships', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('ReferralProgram::module.referral_relationship.title');"]],
                    'data' => [],
                ]
            ]
        ],
        'referral_link' => [
            'presenter' => \Corals\Modules\Referral\Transformers\ReferralLinkPresenter::class,
            'resource_route' => 'referral-programs.referral-links.index',
            'resource_relation' => 'referral_program',
            'relation' => 'referral_link'
        ],
        'referral_relationship' => [
            'presenter' => \Corals\Modules\Referral\Transformers\ReferralRelationshipPresenter::class,
            'resource_route' => 'referral-programs.referral-relationships.index',
            'resource_relation' => 'referral_program',
            'relation' => 'referral_relationship',
            'actions' => [
                'edit' => [],
            ]
        ],
    ]
];
