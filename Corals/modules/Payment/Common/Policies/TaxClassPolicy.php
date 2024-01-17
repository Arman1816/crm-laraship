<?php

namespace Corals\Modules\Payment\Common\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Payment\Common\Models\TaxClass;
use Corals\User\Models\User;

class TaxClassPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.payment';

    /**
     * @param User $user
     * @param TaxClass|null $taxClass
     */
    public function view(User $user, TaxClass $taxClass = null)
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
     * @param TaxClass $taxClass
     */
    public function update(User $user, TaxClass $taxClass)
    {
    }

    /**
     * @param User $user
     * @param TaxClass $taxClass
     */
    public function destroy(User $user, TaxClass $taxClass)
    {
    }
}
