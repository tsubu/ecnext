<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class AdminRole extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get the admins associated with the role.
     */
    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    /**
     * Get the permissions associated with the role.
     */
    public function permissions()
    {
        return $this->hasMany(AdminRolePermission::class);
    }
}
