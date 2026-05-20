<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockPreset extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_type_id',
        'name',
        'settings',
    ];

    protected $casts = [
        'settings' => 'json',
    ];

    /**
     * Get the block type associated with the preset.
     */
    public function blockType()
    {
        return $this->belongsTo(BlockType::class);
    }
}
