<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Inquiry extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
    ];

    /**
     * Get user-friendly status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => __('Pending'),
            'replied' => __('Replied'),
            'closed' => __('Closed'),
            default => $this->status,
        };
    }
}
