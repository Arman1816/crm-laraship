<?php

namespace Corals\Modules\Payment\Common\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Payment\Common\Models\Currency;
use Corals\User\Models\User;

class CurrencyPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.payment';

    /**
     * @param User $user
     * @param Currency|null $currency
     */
    public function view(User $user, Currency $currency = null)
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
     * @param Currency $currency
     */
    public function update(User $user, Currency $currency)
    {
    }

    /**
     * @param User $user
     * @param Currency $currency
     */
    public function destroy(User $user, Currency $currency)
    {
    }
}
