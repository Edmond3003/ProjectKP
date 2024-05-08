<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'hdr_pm',
        'id_barang',
        'qty'
     ];

     public function masteratk()
    {
        return $this->belongsTo(masterAtk::class, 'id_barang');
    }

    public function header()
    {
        return $this->belongsTo(headerPembelian::class, 'hdr_pm');
    }
}
