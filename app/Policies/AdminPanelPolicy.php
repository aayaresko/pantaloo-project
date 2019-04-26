<?php

namespace App\Policies;

use App\User;
use Helpers\GeneralHelper;
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
        $adminPanel = config('adminPanel');
        $allowed_ips = isset($adminPanel['allowed_ips']) ? $adminPanel['allowed_ips'] : [];

        $visitor_ip = GeneralHelper::visitorIpCloudFire();

        $ip_allowed = $allowed_ips == [] || in_array($visitor_ip, $allowed_ips);

        return $user->role >= 2 and $user->role <= 10 and $ip_allowed;
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
