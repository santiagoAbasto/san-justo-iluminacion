<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $guarded = [];

    public function imagenes()
    {
        return $this->hasMany(ImagenProducto::class, 'producto_id');
    }

    public function getImageAttribute($value)
    {
        return url("storage/" . $value);
    }

    public function uso()
    {
        return $this->belongsTo(Uso::class, 'uso_id');
    }

    public function espacio()
    {
        return $this->belongsTo(Espacio::class, 'espacio_id');
    }

    public function linea()
    {
        return $this->belongsTo(Linea::class, 'linea_id');
    }

    public function ambientes()
    {
        return $this->belongsToMany(Ambiente::class, 'producto_ambientes')->select('ambientes.*')->distinct();
    }

    public function colores()
    {
        return $this->belongsToMany(Color::class, 'producto_colors');
    }
}
