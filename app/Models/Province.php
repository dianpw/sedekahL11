<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    protected $primaryKey = 'id_provinsi';
    public $incrementing = false; // ID tidak auto-increment
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'id_provinsi',
        'name',
    ];

    public $timestamps = false; // Tabel referensi umumnya tidak perlu timestamps

    // Relasi ke regencies
    public function regencies()
    {
        return $this->hasMany(Regency::class, 'province_id', 'id_provinsi');
    }
}
