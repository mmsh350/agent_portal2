<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'bvn_enrollments';
    protected $fillable = [
        'user_id',
        'refno',
        'fullname',
        'state',
        'lga',
        'address',
        'city',
        'bvn',
        'account_number',
        'account_name',
        'bank_name',
        'email',
        'phone_number',
        'username',
        'status',
        'reason',
    ];
}
