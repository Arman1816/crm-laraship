<?php

namespace Corals\Modules\Payment\Common\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Payment\Common\Models\Tax;
use Corals\User\Models\User;

class TaxPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.payment';

    /**
     * @param User $user
     * @param Tax|null $tax
     */
    public function view(User $user, Tax $tax = null)
    {
    }

    /**
     * @param User $user
     */
    public function create(User $user)
    {
    }

    /**
     * @param User $user
     * @param Tax $tax
     */
    public function update(User $user, Tax $tax)
    {
    }

    /**
     * @param User $user
     * @param Tax $tax
     */
    public function destroy(User $user, Tax $tax)
    {
    }
}
