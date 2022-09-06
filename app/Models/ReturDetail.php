<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturDetail extends Model
{
    use HasFactory;
    protected $table = 'retur_detail';
    protected $primaryKey = 'id_retur_detail';
    protected $guarded = [];

    public function retur()
    {
        return $this->hasOne(retur::class, 'id_retur', 'id_retur');
    }
    public function produk()
    {
        return $this->hasOne(Produk::class, 'id_produk', 'id_produk');
    }
}
