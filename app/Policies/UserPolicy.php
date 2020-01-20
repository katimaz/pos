<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function hasInventoryAccess(User $user)
    {
        return $this->checkPermission($user,"access.inventory");
    }

    public function hasOrderAccess(User $user)
    {
        return $this->checkPermission($user,"access.order");
    }

    public function hasProductAccess(User $user)
    {
        return $this->checkPermission($user,"access.product");
    }

    public function hasCustomerAccess(User $user)
    {
        return $this->checkPermission($user,"access.customer");
    }

    public function hasReportAccess(User $user)
    {
        return $this->checkPermission($user,"access.report");
    }

    public function hasSettingAccess(User $user)
    {
        return $this->checkPermission($user,"access.setting");
    }

    public function hasTemplateAccess(User $user)
    {
        return $this->checkPermission($user,"access.template");
    }

    public function hasCategoryAccess(User $user)
    {
        return $this->checkPermission($user,"access.category");
    }

    public function hasOrderTypeAccess(User $user)
    {
        return $this->checkPermission($user,"access.ordertype");
    }

    public function hasCountryAccess(User $user)
    {
        return $this->checkPermission($user,"access.country");
    }

    public function hasCurrencyAccess(User $user)
    {
        return $this->checkPermission($user,"access.currency");
    }

    public function hasUserAccess(User $user)
    {
        return $this->checkPermission($user,"access.user");
    }

    public function hasDashboardAccess(User $user)
    {
        return $this->checkPermission($user,"access.dashboard");
    }

    public function hasRoleAccess(User $user)
    {
        return $this->checkPermission($user,"access.role");
    }

    function checkPermission($user,$access){
        $permissions= $user->getUserPermissions();
        foreach($permissions as $permission){
            if($permission->permission_name == $access){
                return true;
            }
        }
        return false;
    }
}
