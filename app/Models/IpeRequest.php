<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'trackingId',
        'reply',
    ];
}
