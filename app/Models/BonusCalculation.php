<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class BonusCalculation extends Model
{
    use HasFactory;

    protected $table = 'bonus_calculations';
    protected $primaryKey = 'id_bonus_calculations';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'registration_id',
        'bonus_member_username',
        'level',
        'amount',
        'calculation_date',
        'status',
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

    // Relasi ke registrasi
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id', 'id_registrations');
    }
}
