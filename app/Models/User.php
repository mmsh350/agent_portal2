<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'referral_code',
        'referral_bonus',
        'referred_by',
        'claim_id',
        'profile_pic',
        'idType',
        'idNumber',
        'role',
        'is_active',
        'kyc_status',
        'wallet_is_created',
        'vwallet_is_created',
        'current_sign_in_at',
        'last_sign_in_at',
        'created_by',
        'deleted_at',
        'deleted_by'
    ];


    protected $attributes = ['is_active' => true];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['total_bonus_amount'];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'wallet_is_created' => 'boolean',
            'vwallet_is_created' => 'boolean',
        ];
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function virtualAccount()
    {
        return $this->hasMany(VirtualAccount::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function bonusHistories()
    {
        return $this->hasMany(BonusHistory::class, 'user_id');
    }

    public function verifications()
    {
        return $this->hasMany(Verification::class);
    }

    public function scopeExcludeAdmin($query)
    {
        return $query->where('id', '!=', 1);
    }
}
