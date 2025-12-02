<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $table = 'withdrawal_requests';
    protected $primaryKey = 'id_withdrawal_requests';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'requested_at',
        'processed_at',
        'paid_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
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

    // Relasi ke payment_history (jika status paid)
    public function paymentHistory()
    {
        return $this->hasOne(PaymentHistory::class, 'reference_id', 'id_withdrawal_requests');
    }
}
