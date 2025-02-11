<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSetting extends Model
{
    protected $table = 'content_settings';

    protected $fillable = [
        'key',
        'value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
