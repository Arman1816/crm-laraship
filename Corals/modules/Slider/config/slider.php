<?php

return [
    'models' => [
        'slider' => [
            'presenter' => \Corals\Modules\Slider\Transformers\SliderPresenter::class,
            'resource_url' => 'slider/sliders',
            'actions' => [
                'slides' => [
                    'icon' => 'fa fa-fw fa-film',
                    'href_pattern' => ['pattern' => '[arg]/slides', 'replace' => ['return $object->getShowUrl();']],
                    'label_pattern' => ['pattern' => '[arg]', 'replace' => ["return trans('Slider::module.slide.title');"]],
                    'data' => [],
                ]
            ]
        ],
        'slide' => [
            'presenter' => \Corals\Modules\Slider\Transformers\SlidePresenter::class,
            'resource_route' => 'sliders.slides.index',
            'resource_relation' => 'slider',
            'relation' => 'slide'
        ]
    ]
];
