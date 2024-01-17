<?php

namespace Corals\User\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Settings\Facades\Modules;
use Corals\User\Models\User;
use Corals\User\Models\User as UserModel;

class UserPolicy extends BasePolicy
{
    /**
     * @var array
     */
    protected $skippedAbilities = [
        'destroy',
        'update',
        'impersonate',
        'sendSMS'
    ];

    /**
     * @var string
     */
    protected $administrationPermission = 'Administrations::admin.user';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('User::user.view')) {
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
        return $user->can('User::user.create');
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function update(User $user, UserModel $usermodel)
    {
        if (!isSuperUser() && isSuperUser($usermodel)) {
            return false;
        }

        if ($user->can('User::user.update') || isSuperUser()) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param UserModel $usermodel
     * @return bool
     */
    public function destroy(User $user, UserModel $usermodel)
    {
        if (isSuperUser($usermodel) || $usermodel->id == $user->id) {
            return false;
        }

        if ($user->can('User::user.delete') || isSuperUser()) {
            return true;
        }
        return false;
    }

    public function impersonate(User $user, UserModel $userModel)
    {
        return isSuperUser($user);
    }

    /**
     * @param UserModel $user
     * @param UserModel $userModel
     * @return bool
     */
    public function sendSMS(User $user, UserModel $userModel): bool
    {
        return Modules::isModuleActive('corals-sms') && !is_null($userModel->getPhoneNumber());
    }
}
