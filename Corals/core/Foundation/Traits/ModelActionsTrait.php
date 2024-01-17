<?php

namespace Corals\Foundation\Traits;

use Corals\Activity\Models\Activity;
use Corals\Foundation\Facades\ModelActionsHandler;
use Illuminate\Support\Arr;

trait ModelActionsTrait
{
    /**
     * @param bool $isDatatable
     * @param null $view
     * @param array $only
     * @return array|string
     */
    public function getActions($isDatatable = false, $view = null, $only = [])
    {
        if ($this->archived ?? false) {
            return '';
        }

        $actions = $this->getConfig('actions');

        if (!$actions || !is_array($actions)) {
            $actions = [];
        }

        $actions = array_merge($this->getCommonActions(), $actions);

        if (!empty($only)) {
            $actions = Arr::only($actions, $only);
        }

        foreach ($actions as $index => $action) {
            $actions[$index] = $this->getAction($action);
        }

        if (!$isDatatable) {
            $actions = array_filter($actions, function ($action) {
                if (isset($action['datatable_only'])) {
                    return false;
                }

                return true;
            });
        }

        if ($view || !$isDatatable) {
            $actions = ModelActionsHandler::renderActions($actions, $view);
        }

        return $actions;
    }

    public function getCommonActions()
    {
        return [
            'edit' => [
                'icon' => 'fa fa-fw fa-pencil',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getEditURL();']],
                'class' => 'btn btn-primary btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.edit");']
                ],
                'policies' => ['update'],
                'permissions' => [],
                'data' => []
            ],
            'delete' => [
                'datatable_only' => true,
                'icon' => 'fa fa-fw fa-remove',
                'href_pattern' => ['pattern' => '[arg]', 'replace' => ['return $object->getShowURL();']],
                'class' => 'btn btn-danger btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.delete");']
                ],
                'policies' => ['destroy'],
                'permissions' => [],
                'data' => [
                    'action' => 'delete',
                    'table' => '.dataTableBuilder'
                ]
            ],
            'activity_log' => [
                'icon' => 'fa fa-history fa-fw',
                'href_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return url("activities/".str_replace("\\\","-",getMorphAlias($object))."/".$object->hashed_id);']
                ],
                'class' => 'btn btn-info btn-sm',
                'label_pattern' => [
                    'pattern' => '[arg]',
                    'replace' => ['return trans("Corals::labels.without_icon.logs");']
                ],
                'policies_model' => Activity::class,
                'policies' => ['view'],
                'policies_args' => null,
                'permissions' => [],
                'data' => [
                    'action' => 'modal-load',
                    'title' => "<i class='fa fa-history fa-fw'></i> Activity Log",
                    'modal_class' => 'modal-x',
                ]
            ]
        ];
    }

    public function getAction($action)
    {
        if (!ModelActionsHandler::isActionVisible($action, $this)) {
            return null;
        }

        return ModelActionsHandler::solveActionPatterns($action, $this);
    }

    public function getActionByName($name, $view = null)
    {
        if (!$action = $this->getConfig("actions.$name")) {
            return null;
        }

        if ($action = $this->getAction($action)) {
            return ModelActionsHandler::renderActions([$name => $action], $view);
        }

        return null;
    }


    /**
     * @param array $names
     * @param null $view
     * @return string
     */
    public function getActionsByNames(array $names, $view = null)
    {
        foreach ($names as $name) {
            $actions[] = $this->getActionByName($name, $view);
        }

        return join('', $actions ?? []);
    }
}
