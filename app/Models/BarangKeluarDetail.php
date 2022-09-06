<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluarDetail extends Model
{
    use HasFactory;
    protected $table = 'barang_keluar_detail';
    protected $primaryKey = 'id_barang_keluar_detail';
    protected $guarded = [];


    public function produk()
    {
        return $this->hasOne(Produk::class, 'id_produk', 'id_produk');
    }
}
