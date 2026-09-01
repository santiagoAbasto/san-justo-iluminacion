<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $guarded = [];

    public function getImageAttribute($value)
    {
        return url("storage/" . $value);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class)->orderBy('order', 'asc');
    }

    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
