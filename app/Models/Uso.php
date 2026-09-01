<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uso extends Model
{
    protected $guarded = [];

    public function produtos()
    {
        return $this->hasMany(Producto::class);
    }

    public function espacio()
    {
        return $this->belongsTo(Espacio::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
