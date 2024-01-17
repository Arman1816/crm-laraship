<?php

namespace Corals\Modules\Referral\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Referral\Models\ReferralRelationship;
use Corals\User\Models\User;

class ReferralRelationshipPolicy extends BasePolicy
{
    protected $skippedAbilities = [
        'destroy'
    ];

    public function view(User $user)
    {
        return user()->can('Referral::referral_relationship.view');
    }

    /**
     * @param User $user
     * @param ReferralRelationship $referralRelationship
     * @return bool
     */
    public function destroy(User $user, ReferralRelationship $referralRelationship)
    {
        return user()->can('Referral::referral_relationship.destroy');
    }

}
