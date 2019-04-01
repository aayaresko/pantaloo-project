<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPanelPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function accessUserTranslator(User $user)
    {
        return $user->role == 10;
    }

    public function accessUserAdmin(User $user)
    {
        return $user->role == 2;
    }

    public function accessUserAdminPublic(User $user)
    {
        return $user->role >= 2 and $user->role <= 10;
    }

    public function accessUserAffiliate(User $user)
    {
        return $user->role == 3;
    }

    public function accessAdminAffiliatePublic(User $user)
    {
        return $user->role >= 2 and $user->role <= 3;
    }

    public function accessAdminTranslatorPublic(User $user)
    {
        return $user->role == 2 or $user->role == 10;
    }
}
