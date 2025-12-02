<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_history';
    protected $primaryKey = 'id_payment_history';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'reference_id',
        'payment_method',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
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

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_users');
    }

    // Relasi ke withdrawal_request (jika type = withdrawal)
    public function withdrawalRequest()
    {
        return $this->belongsTo(WithdrawalRequest::class, 'reference_id', 'id_withdrawal_requests');
    }

    // Relasi ke bonus_calculation (jika type = manual_bonus) - Perlu ID yang benar
    // public function bonusCalculation()
    // {
    //     // Karena reference_id bisa ke withdrawal atau bonus, ini perlu pendekatan berbeda
    //     // Misalnya polymorphic relation atau cek type dulu
    //     if ($this->type === 'manual_bonus') {
    //         return $this->belongsTo(BonusCalculation::class, 'reference_id', 'id_bonus_calculations');
    //     }
    //     return null;
    // }
}
