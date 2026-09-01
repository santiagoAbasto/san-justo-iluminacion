<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComercioExterior extends Model
{
    protected $guarded = [];

    public function getImageSeccionDosAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getImageSeccionTresAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getImageSeccionTresDosAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getImageSeccionTresTresAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
