<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPortada extends Model
{
    protected $guarded = [];

    public function getImageBannerAttribute($value)
    {
        return url("storage/" . $value);
    }

    public function getImageSeccionUnoAttribute($value)
    {
        return url("storage/" . $value);
    }

    public function getImageSeccionDosAttribute($value)
    {
        return url("storage/" . $value);
    }

    public function getImageSeccionTresAttribute($value)
    {
        return url("storage/" . $value);
    }

    public function getCustomImageEsAttribute($value)
{
    return $value ? url("storage/" . $value) : null;
}
}
