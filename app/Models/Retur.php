<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    use HasFactory;

    protected $table = 'retur';
    protected $primaryKey = 'id_retur';
    protected $guarded = [];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id penjualan');
    }
}
