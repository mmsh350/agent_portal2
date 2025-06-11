<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'idno',
        'type',
        'nin',
        'first_name',
        'middle_name',
        'last_name',
        'phoneno',
        'email',
        'dob',
        'gender',
        'address',
        'enrollment_branch',
        'enrollment_bank',
        'photo',
        'registration_date',
        'title',
        'state',
        'lga',
        'town',
        'residence_state',
        'residence_lga',
        'residence_town',
        'trackingId',
        'signature'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
