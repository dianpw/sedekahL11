<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class MemberProductAccess extends Model
{
    use HasFactory;

    protected $table = 'member_product_access';
    protected $primaryKey = 'id_member_product_access';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'user_id',
        'product_id',
        'granted_at',
    ];

    protected $casts = [
        'granted_at' => 'datetime',
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

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(DigitalProduct::class, 'product_id', 'id_digital_products');
    }
}
