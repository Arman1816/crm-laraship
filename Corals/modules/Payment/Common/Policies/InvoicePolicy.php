<?php

namespace Corals\Modules\Payment\Common\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Payment\Common\Models\Invoice;

class InvoicePolicy extends BasePolicy
{

    protected $administrationPermission = 'Administrations::admin.payment';

    protected $skippedAbilities = [
        'payOrder', 'update'
    ];

    /**
     * @param User $user
     * @param Invoice|null $invoice
     * @return bool
     */
    public function view(User $user, Invoice $invoice = null)
    {
        if ($user->can('Payment::invoices.view_all')) {
            return true;
        }

        if ($user->can('Payment::invoices.view') && $invoice) {
            if ((optional($invoice->user)->id == $user->id)) {
                return true;

            }

            if (isset($invoice->invoicable->generator) && $invoice->invoicable->generator->id == $user->id) {
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
        return $user->can('Payment::invoices.create');
    }

    /**
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function update(User $user, Invoice $invoice)
    {
        return $user->can('Payment::invoices.update');
    }

    /**
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function destroy(User $user, Invoice $invoice)
    {
        return $user->can('Payment::invoices.delete');
    }

    /**
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function sendInvoice(User $user, Invoice $invoice)
    {
        return in_array($invoice->status, ['pending']) && !$invoice->is_sent
            && (
                $user->can('Payment::invoices.update')
                || $user->hasPermissionTo('Administrations::admin.payment')
                || isSuperUser()
            );
    }

    /**
     * @param User $user
     * @param Invoice $invoice
     * @return bool
     */
    public function payOrder(User $user, Invoice $invoice)
    {
        if ($invoice->status != 'paid' && \Modules::isModuleActive('corals-ecommerce')) {

            if ($invoice->invoicable && get_class($invoice->invoicable) == \Corals\Modules\Ecommerce\Models\Order::class
                && user()->can('payOrder', $invoice->invoicable)) {
                return true;
            }
        }

        return false;
    }
}
