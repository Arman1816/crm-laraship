<?php

namespace Corals\Modules\CMS\Services;

use Corals\Foundation\Services\BaseServiceClass;

class CategoryService extends BaseServiceClass
{
    protected $excludedRequestParams = ['subscription_plans'];

    public function postStoreUpdate($request, $additionalData)
    {
        $category = $this->model;

        if (\Modules::isModuleActive('corals-subscriptions')) {
            $plans = [];

            $subscribable_plans = $request->input('subscription_plans');

            if (is_array($subscribable_plans)) {

                foreach ($subscribable_plans as $subscribable_plan) {
                    $plans[] = [
                        'plan_id' => $subscribable_plan,
                        'subscribable_id' => $category->id,
                        'subscribable_type' => get_class($category),

                    ];
                }
            }

            $category->subscribable_plans()->sync($plans);
        }
    }
}
