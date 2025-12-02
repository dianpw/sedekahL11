<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';
    protected $primaryKey = 'id_registrations';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'new_member_id',
        'sponsor_username',
        'upline_username',
        'registration_type',
        'marketing_pin_id',
        'registration_fee',
        'admin_fee',
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

    // Relasi ke member baru
    public function newMember()
    {
        return $this->belongsTo(User::class, 'new_member_id', 'id_users');
    }

    // Relasi ke PIN Marketing (jika daftar marketing)
    public function marketingPin()
    {
        return $this->belongsTo(MarketingPin::class, 'marketing_pin_id', 'id_marketing_pins');
    }

    // Relasi ke bonus calculations
    public function bonusCalculations()
    {
        return $this->hasMany(BonusCalculation::class, 'registration_id', 'id_registrations');
    }
}
