<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $casts = [
        'is_important' => 'boolean'
    ];
}
