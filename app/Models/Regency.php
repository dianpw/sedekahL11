<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    protected $table = 'regencies';
    protected $primaryKey = 'id_kab_kota';
    public $incrementing = false; // ID tidak auto-increment
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'id_kab_kota',
        'province_id',
        'name',
    ];

    public $timestamps = false; // Tabel referensi umumnya tidak perlu timestamps

    // Relasi ke province
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id_provinsi');
    }

    // Relasi ke districts
    public function districts()
    {
        return $this->hasMany(District::class, 'regency_id', 'id_kab_kota');
    }
}
