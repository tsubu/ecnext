<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'action',
        'auditable_id',
        'auditable_type',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
    ];

    /**
     * Get the model that was audited (Polymorphic).
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * Get the performing user (Admin or Customer).
     */
    public function user()
    {
        return $this->morphTo('user', 'user_type', 'user_id');
    }
}
