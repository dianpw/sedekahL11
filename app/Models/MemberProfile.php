<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid; // Import Uuid

class MemberProfile extends Model
{
    use HasFactory;

    protected $table = 'member_profiles';
    protected $primaryKey = 'id_member_profiles';
    public $incrementing = false; // Disable auto-incrementing
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'address',
        'id_sponsor',
        'id_upline',
        'provinsi_id',
        'kab_kota_id',
        'kecamatan_id',
        'desa_id',
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

    // Relasi ke provinsi
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinsi_id', 'id_provinsi');
    }

    // Relasi ke kab/kota
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'kab_kota_id', 'id_kab_kota');
    }

    // Relasi ke kecamatan
    public function district()
    {
        return $this->belongsTo(District::class, 'kecamatan_id', 'id_kecamatan');
    }

    // Relasi ke desa
    public function village()
    {
        return $this->belongsTo(Village::class, 'desa_id', 'id_desa');
    }
}
