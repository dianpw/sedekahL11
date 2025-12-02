<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class DigitalProduct extends Model
{
    use HasFactory;

    protected $table = 'digital_products';
    protected $primaryKey = 'id_digital_products';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_type',
        'access_level',
        'created_by',
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

    // Relasi ke admin pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_users');
    }

    // Relasi ke member_product_access
    public function memberAccess()
    {
        return $this->hasMany(MemberProductAccess::class, 'product_id', 'id_digital_products');
    }
}
