<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Linea extends Model
{
    protected $guarded = [];

    protected $table = "lineas";

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function ambientes()
    {
        return $this->belongsToMany(Ambiente::class, 'linea_ambientes')->select('ambientes.*')->distinct();
    }
}
