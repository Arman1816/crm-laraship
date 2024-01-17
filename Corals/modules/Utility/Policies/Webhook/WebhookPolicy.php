<?php

namespace Corals\Modules\Utility\Policies\Webhook;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Utility\Models\Webhook\Webhook;
use Corals\User\Models\User;

class WebhookPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.utility';

    protected $skippedAbilities = [
        'process'
    ];

    public function view(User $user)
    {
        return $user->can('Utility::webhook.view');
    }

    /**
     * @param User $user
     * @param Webhook $webhook
     * @return bool
     */
    public function process(User $user, Webhook $webhook)
    {
        if (in_array($webhook->status, ['pending', 'failed']) && $user->can('Utility::webhook.process')) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param Webhook $webhook
     * @return bool
     */
    public function destroy(User $user, Webhook $webhook)
    {
        return $user->can('Utility::webhook.delete');
    }
}
