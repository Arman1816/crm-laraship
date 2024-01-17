<?php

namespace Corals\Modules\Referral\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Referral\Models\ReferralProgram;

class ReferralProgramPolicy extends BasePolicy
{

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Referral::referral_program.view')) {
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
        return $user->can('Referral::referral_program.create');
    }

    /**
     * @param User $user
     * @param ReferralProgram $referral_program
     * @return bool
     */
    public function update(User $user, ReferralProgram $referral_program)
    {
        if ($user->can('Referral::referral_program.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param ReferralProgram $referral_program
     * @return bool
     */
    public function destroy(User $user, ReferralProgram $referral_program)
    {
        if ($user->can('Referral::referral_program.delete')) {
            return true;
        }
        return false;
    }

}
