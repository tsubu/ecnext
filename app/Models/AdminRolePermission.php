<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRolePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_role_id',
        'permission_key',
    ];

    /**
     * Get the role associated with the permission record.
     */
    public function role()
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }
}
