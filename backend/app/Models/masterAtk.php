<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class masterAtk extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_barang',
        'id_kelompok',
        'total',
     ];

     public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok');
    }
}
