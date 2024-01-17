<?php

namespace Corals\User\Models;

use Corals\Foundation\Traits\HashTrait;
use Corals\Foundation\Traits\Hookable;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Settings\Traits\CustomFieldsModelTrait;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HashTrait, Hookable, ModelPropertiesTrait, CustomFieldsModelTrait;

    protected $casts = [
        'properties' => 'json'
    ];
}