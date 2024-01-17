<?php

namespace Corals\Modules\CMS\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\CMS\Models\Faq;

class FaqPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.cms';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('CMS::faq.view')) {
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
        return $user->can('CMS::faq.create');
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function update(User $user, Faq $faq)
    {
        if ($user->can('CMS::faq.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Faq $faq
     * @return bool
     */
    public function destroy(User $user, Faq $faq)
    {
        if ($user->can('CMS::faq.delete')) {
            return true;
        }
        return false;
    }

}
