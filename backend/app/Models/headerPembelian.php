<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class headerPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'tgl',
        'id_suplier',
        'status'
     ];

     public function suplier()
    {
        return $this->belongsTo(Suplier::class, 'id_suplier');
    }
}
