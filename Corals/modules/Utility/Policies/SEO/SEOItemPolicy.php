<?php

namespace Corals\Modules\Utility\Policies\SEO;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Utility\Models\SEO\SEOItem;
use Corals\User\Models\User;

class SEOItemPolicy extends BasePolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Utility::seo_item.view')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Utility::seo_item.create');
    }

    /**
     * @param User $user
     * @param SEOItem $seo_item
     * @return bool
     */
    public function update(User $user, SEOItem $seo_item)
    {
        if ($user->can('Utility::seo_item.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param SEOItem $seo_item
     * @return bool
     */
    public function destroy(User $user, SEOItem $seo_item)
    {
        if ($user->can('Utility::seo_item.delete')) {
            return true;
        }
        return false;
    }
}
