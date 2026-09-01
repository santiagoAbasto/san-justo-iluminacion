<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    protected $guarded = [];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function usos()
    {
        return $this->hasMany(Uso::class, 'espacio_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
