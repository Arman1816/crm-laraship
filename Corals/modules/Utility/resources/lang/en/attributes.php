<?php

return [

    'location' => [
        'name' => 'Name',
        'lat' => 'Latitude',
        'long' => 'Longitude',
        'zip' => 'zip',
        'type' => 'Location Type',
        'parent' => 'Parent Location',
        'address' => 'Address',
        'address_placeholder' => 'Search Google address or type address here.',
        'city' => 'City',
        'state' => 'State',
        'country' => 'Country',
        'description' => 'Description',
        'slug' => 'Slug',
        'slug_help' => 'Slug will be filled automatically if left blank',
    ],
    'wishlist' => [
        'object' => 'Object'
    ],
    'tag' => [
        'name' => 'Name',
        'slug' => 'Slug',
        'module' => 'Module',
        'icon' => 'Icon Class',
        'clear' => 'Clear thumbnail image',
        'thumbnail' => 'Thumbnail',
    ],
    'attributes' => [
        'label' => 'Label',
        'type' => 'Type',
        'required' => 'Required',
        'use_as_filter' => 'Use as filter',
        'order' => 'Order',
        'options' => 'Options',
        'icon' => 'Icon Class',
        'clear' => 'Clear thumbnail image',
        'thumbnail' => 'Thumbnail',
    ],
    'category' => [
        'name' => 'Name',
        'slug' => 'Slug',
        'parent_id' => 'Parent',
        'products_count' => '# Products',
        'parent_cat' => 'Parent Category',
        'is_featured' => 'Featured',
        'description' => 'Description',
        'clear' => 'Clear thumbnail image',
        'thumbnail' => 'Thumbnail',
        'attributes' => 'Attributes',
        'thumbnail_link' => 'Thumbnail Link'
    ],

    'seo_item' => [
        'slug' => 'Slug',
        'route' => 'Route',
        'title' => 'Title',
        'type' => 'Type',
        'meta_keywords' => 'Meta Keywords',
        'meta_description' => 'Meta Description',
        'image' => 'Image',
        'clear' => 'Clear',
    ],

    'rating' => [
        'title' => 'Title',
        'body' => 'Body',
        'rating' => 'Rating',
        'author' => 'Author',
        'model' => 'Model',
        'type' => 'Type ',
        'comments_count' => 'Comments',
        'status_options' => [
            'approved' => 'Approved',
            'disapproved' => 'Disapproved',
            'pending' => 'Pending',
            'spam' => 'Spam',
        ],
    ],
    'comments' => [
        'title' => 'Object Title',
        'object' => 'Object',
        'body' => 'Body',
        'author' => 'Author',
        'is_private' => 'Is private? <small> Customer won\'t get notified or be able to see the comment</small>',
        'private' => 'Private',
        'status_options' => [
            'pending' => 'Pending',
            'published' => 'Published',
            'trashed' => 'Trashed',
        ],
    ],
    'update_status' => 'Status has been update.',
    'no_permission' => 'There is no permission update status',
    'webhook' => [
        'event_name' => 'Event',
        'payload' => 'Payload',
        'exception' => 'Exception',
        'properties' => 'Properties',
        'status_options' => [
            'pending' => 'Pending',
            'processed' => 'Processed',
            'partially_processed' => 'Partially Processed',
            'failed' => 'Failed',
        ],
    ],
    'listOfValue' => [
        'code' => 'Code',
        'code_help' => 'Auto generated when empty',
        'value' => 'Value',
        'module' => 'Module',
        'parent' => 'Parent',
        'display_order' => 'Display Order',
        'label' => 'Label',
        'hidden' => 'Is hidden?'
    ],
    'guide' => [
        'url' => 'URL',
        'route' => 'Route',
        'intro' => 'Intro',
        'element' => 'Element',
        'position' => 'Position'
    ],
];
