<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;

class PointService
{
    /**
     * Add points to a user.
     */
    public function addPoints(User $user, float $amount, string $reason, $reference = null)
    {
        return DB::transaction(function () use ($user, $amount, $reason, $reference) {
            $user->increment('points', $amount);
            
            return $user->pointTransactions()->create([
                'amount' => $amount,
                'balance' => $user->fresh()->points,
                'reason' => $reason,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
            ]);
        });
    }

    /**
     * Deduct points from a user.
     */
    public function deductPoints(User $user, float $amount, string $reason, $reference = null)
    {
        if ($user->points < $amount) {
            throw new \Exception('Insufficient points.');
        }

        return DB::transaction(function () use ($user, $amount, $reason, $reference) {
            $user->decrement('points', $amount);
            
            return $user->pointTransactions()->create([
                'amount' => -$amount,
                'balance' => $user->fresh()->points,
                'reason' => $reason,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference ? $reference->id : null,
            ]);
        });
    }
}
