<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'subject',
        'body_html',
        'body_text',
    ];
}
