<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaAmbiente extends Model
{
    protected $guarded = [];

    public function linea()
    {
        return $this->belongsTo(Linea::class);
    }

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class);
    }
}
