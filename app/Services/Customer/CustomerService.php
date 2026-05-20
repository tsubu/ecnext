<?php

namespace App\Services\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerService
{
    /**
     * Update customer profile data.
     */
    public function updateProfile(User $user, array $data): User
    {
        if (isset($data['email']) && $data['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->fill($data);
        $user->save();

        return $user;
    }

    /**
     * Update customer password.
     */
    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Delete customer account.
     */
    public function deleteAccount(User $user): void
    {
        $user->delete();
    }
}
