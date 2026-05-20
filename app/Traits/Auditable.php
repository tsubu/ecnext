<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->recordAudit('created');
        });

        static::updated(function ($model) {
            $model->recordAudit('updated');
        });

        static::deleted(function ($model) {
            $model->recordAudit('deleted');
        });
    }

    /**
     * Record an audit log for this model.
     */
    protected function recordAudit(string $action)
    {
        $oldValues = null;
        $newValues = null;

        if ($action === 'updated') {
            $newValues = $this->getDirty();
            $oldValues = array_intersect_key($this->getOriginal(), $newValues);
        } elseif ($action === 'created') {
            $newValues = $this->getAttributes();
        } elseif ($action === 'deleted') {
            $oldValues = $this->getOriginal();
        }

        // Skip sensitive fields
        $sensitiveFields = ['password', 'remember_token', 'secret_key', 'api_token'];
        if ($oldValues) $oldValues = array_diff_key($oldValues, array_flip($sensitiveFields));
        if ($newValues) $newValues = array_diff_key($newValues, array_flip($sensitiveFields));

        // If updated but no relevant changes (after filtering), skip
        if ($action === 'updated' && empty($newValues)) {
            return;
        }

        $userId = null;
        $userType = null;

        if (Auth::guard('admin')->check()) {
            $userId = Auth::guard('admin')->id();
            $userType = 'admin';
        } elseif (Auth::check()) {
            $userId = Auth::id();
            $userType = 'user';
        } else {
            $userId = 0;
            $userType = 'system';
        }

        AuditLog::create([
            'user_type' => $userType,
            'user_id' => $userId,
            'action' => $action,
            'auditable_id' => $this->getKey(),
            'auditable_type' => get_class($this),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
