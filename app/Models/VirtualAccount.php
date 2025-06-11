<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    protected $fillable = [
        'user_id',
        'accountReference',
        'accountNo',
        'accountName',
        'bankName',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
