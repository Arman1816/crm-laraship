<?php

namespace Corals\User\Classes;

use Corals\User\Models\Permission;
use Corals\User\Models\Role;

class Roles
{
    /**
     * Roles constructor.
     */
    function __construct()
    {
    }

    public function getRolesList($options = [])
    {
        $key = $options['key'] ?? 'id';
        $roles = Role::pluck('label', $key);

        return $roles;
    }



    public function getPermissionsTree()
    {
        $tree = [];

        $permissions = Permission::get();

        foreach ($permissions as $permission) {
            list($package, $model) = explode('::', $permission->name);

            list($model, $action) = explode('.', $model);

            if (!isset($tree[$package])) {
                $tree[$package] = [];
            }
            if (!isset($tree[$package][$model])) {
                $tree[$package][$model] = [];
            }
            $tree[$package][$model][$permission->id] = $action;
        }

        return $tree;
    }
}
