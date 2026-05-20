<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminAuthService
{
    /**
     * Attempt to authenticate an admin.
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attemptLogin(array $credentials, bool $remember = false): bool
    {
        return Auth::guard('admin')->attempt($credentials, $remember);
    }

    /**
     * Log the admin out of the application.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }

    /**
     * Check if the currently authenticated admin has a specific permission.
     *
     * @param string $permissionKey
     * @return bool
     */
    public function hasPermission(string $permissionKey): bool
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin || !$admin->role) {
            return false;
        }

        return $admin->role->permissions()
            ->where('permission_key', $permissionKey)
            ->exists();
    }
}
