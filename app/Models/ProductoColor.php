<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoColor extends Model
{
    protected $guarded = [];


    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
