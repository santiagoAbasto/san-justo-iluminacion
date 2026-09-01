<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NosotrosBanner extends Model
{
    protected $guarded = [];

    public function getMediaAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
