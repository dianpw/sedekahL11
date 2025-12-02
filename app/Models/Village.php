<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $table = 'villages';
    protected $primaryKey = 'id_desa';
    public $incrementing = false; // ID tidak auto-increment
    protected $keyType = 'string'; // Set primary key type to string

    protected $fillable = [
        'id_desa',
        'district_id',
        'name',
    ];

    public $timestamps = false; // Tabel referensi umumnya tidak perlu timestamps

    // Relasi ke district
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id_kecamatan');
    }
}
