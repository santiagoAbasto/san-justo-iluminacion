<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NosotrosSecciones extends Model
{
    protected $guarded = [];

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
