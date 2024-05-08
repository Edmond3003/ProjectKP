<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class headerPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tgl',
        'id_cabang',
        'status'
     ];

     public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang');
    }
}
