<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';
    protected $primaryKey = 'id_kecamatan';
    public $incrementing = false; // ID tidak auto-increment
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'id_kecamatan',
        'regency_id',
        'name',
    ];

    public $timestamps = false; // Tabel referensi umumnya tidak perlu timestamps

    // Relasi ke regency
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id', 'id_kab_kota');
    }

    // Relasi ke villages
    public function villages()
    {
        return $this->hasMany(Village::class, 'district_id', 'id_kecamatan');
    }
}
