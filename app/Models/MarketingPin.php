<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class MarketingPin extends Model
{
    use HasFactory;

    protected $table = 'marketing_pins';
    protected $primaryKey = 'id_marketing_pins';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'pin_code',
        'admin_issuer_id',
        'assigned_user_id',
        'valid_until',
        'is_used',
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

    // Relasi ke admin yang mengeluarkan
    public function issuer()
    {
        return $this->belongsTo(User::class, 'admin_issuer_id', 'id_users');
    }

    // Relasi ke member yang diberikan (bisa null)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'id_users');
    }

    // Relasi ke registrasi (jika digunakan)
    public function registration()
    {
        return $this->hasOne(Registration::class, 'marketing_pin_id', 'id_marketing_pins');
    }
}
