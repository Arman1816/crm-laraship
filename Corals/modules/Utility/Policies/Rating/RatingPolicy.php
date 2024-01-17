<?php

namespace Corals\Modules\Utility\Policies\Rating;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Utility\Models\Rating\Rating;
use Corals\User\Models\User;

class RatingPolicy extends BasePolicy
{
    protected $skippedAbilities = [
        'updateStatus'
    ];

    protected $administrationPermission = 'Administrations::admin.utility';

    public function updateStatus(User $user, Rating $rating, $status)
    {

        if ($user->cant('Utility::rating.set_status') && !isSuperUser($user)) {
            return false;
        }
        switch ($status) {
            case 'pending':
                return $rating->canBePending();
                break;
            case 'approved':
                return $rating->canBeApproved();
                break;
            case 'disapproved':
                return $rating->canBeDisApproved();
                break;
            case 'spam':
                return $rating->canBeSpam();
                break;
            default:
                return false;
        }
    }

    public function create(User $user)
    {
        return $user->can('Utility::rating.create');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Utility::rating.view')) {
            return true;
        }
        return false;
    }

    public function update(User $user)
    {
        if ($user->can('Utility::rating.update')) {
            return true;
        }
        return false;
    }

    public function destroy(User $user)
    {
        if ($user->can('Utility::rating.delete')) {
            return true;
        }
        return false;
    }
}
