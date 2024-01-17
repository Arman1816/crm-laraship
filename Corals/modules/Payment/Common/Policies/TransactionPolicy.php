<?php

namespace Corals\Modules\Payment\Common\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Payment\Common\Models\Transaction;
use Corals\User\Models\User;

class TransactionPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.payment';

    protected $skippedAbilities = ['canReverseTransfer'];

    /**
     * @param User $user
     * @param Transaction|null $transaction
     * @return bool
     */
    public function view(User $user, Transaction $transaction = null)
    {
        if ($user->can('Payment::transaction.view_all')) {
            return true;
        }
        if ($user->can('Payment::transaction.view')) {
            if (isset($transaction->owner) && $transaction->owner->id == $user->id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Payment::transaction.create');
    }

    /**
     * @param User $user
     * @param Transaction $transaction
     * @return bool
     */
    public function update(User $user, Transaction $transaction)
    {
        return $user->can('Payment::transaction.update');
    }

    /**
     * @param User $user
     * @param Transaction $transaction
     * @return bool
     */
    public function destroy(User $user, Transaction $transaction)
    {
        return $user->can('Payment::transaction.delete');
    }

    public function canReverseTransfer(User $user, Transaction $transaction)
    {
        $transactionCanBeReversed = $transaction->type == 'payout' && $transaction->reference && $transaction->status == 'completed';

        if (!$transactionCanBeReversed) {
            return false;
        }

        if ($user->hasPermissionTo($this->administrationPermission) || isSuperUser($user)) {
            return true;
        }

        return $transaction->owner_id == $user->id && $transaction->owner_type == getMorphAlias($user);
    }
}
