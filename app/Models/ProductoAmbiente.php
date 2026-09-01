<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoAmbiente extends Model
{
    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class);
    }
}
