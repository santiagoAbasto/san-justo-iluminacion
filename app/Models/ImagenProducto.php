<?php

namespace App\Models;

use App\Services\CatalogThumbnail;
use Illuminate\Database\Eloquent\Model;

class ImagenProducto extends Model
{
    protected $guarded = [];

    public function productos()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getImageAttribute($value)
    {
        return asset("storage/" . $value);
    }

    public function getCatalogThumbnailAttribute(): string
    {
        return app(CatalogThumbnail::class)->url($this->getRawOriginal('image') ?? '');
    }
}
