<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logos extends Model
{
    protected $guarded = [];

    public function getLogoPrincipalAttribute($value)
    {
        return $value ? url("storage/" . $value) : null;
    }
    public function getLogoSecundarioAttribute($value)
    {
        return $value ? url("storage/" . $value) : null;
    }

    public function getLogoTresAttribute($value)
    {
        return $value ? url("storage/" . $value) : null;
    }
}
