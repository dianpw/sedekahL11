<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid; // Import Uuid

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_users';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'dana_account',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model and set the ID before creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = substr(Uuid::uuid4()->toString(), 0, 8);
            }
        });
    }

    // Relasi ke member_profiles
    public function profile()
    {
        return $this->hasOne(MemberProfile::class, 'user_id', 'id_users');
    }

    // Relasi ke pin
    public function pin()
    {
        return $this->hasOne(Pin::class, 'user_id', 'id_users');
    }

    // Relasi ke registrasi sebagai member baru
    public function registrationsAsNewMember()
    {
        return $this->hasMany(Registration::class, 'new_member_id', 'id_users');
    }

    // Relasi ke withdrawal_requests
    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'user_id', 'id_users');
    }

    // Relasi ke payment_history
    public function paymentHistory()
    {
        return $this->hasMany(PaymentHistory::class, 'user_id', 'id_users');
    }

    // Relasi ke member_product_access
    public function productAccess()
    {
        return $this->hasMany(MemberProductAccess::class, 'user_id', 'id_users');
    }

    // Relasi ke logs
    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id', 'id_users');
    }

    // Relasi ke marketing_pins (admin sebagai issuer)
    public function issuedMarketingPins()
    {
        return $this->hasMany(MarketingPin::class, 'admin_issuer_id', 'id_users');
    }

    // Relasi ke marketing_pins (member sebagai assignee)
    public function assignedMarketingPins()
    {
        return $this->hasMany(MarketingPin::class, 'assigned_user_id', 'id_users');
    }

    // Relasi ke digital_products (admin sebagai creator)
    public function createdProducts()
    {
        return $this->hasMany(DigitalProduct::class, 'created_by', 'id_users');
    }
}
