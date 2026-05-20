<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metafield extends Model
{
    use HasFactory;

    protected $fillable = [
        'metafieldable_id',
        'metafieldable_type',
        'namespace',
        'key',
        'value',
        'type',
        'description',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Get the parent model that owns the metafield (Polymorphic).
     */
    public function metafieldable()
    {
        return $this->morphTo();
    }
}
