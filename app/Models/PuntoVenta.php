<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoVenta extends Model
{
    use HasFactory;

    protected $table = 'punto_ventas';

    protected $fillable = [
        'nombre',
        'direccion',
        'provincia',
        'localidad',
        'latitud',
        'longitud',
        'telefono',
        'email',
        'descripcion',
        'activo'
    ];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
        'activo' => 'boolean'
    ];

    // Scope para puntos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para filtrar por provincia
    public function scopePorProvincia($query, $provincia)
    {
        if ($provincia) {
            return $query->where('provincia', $provincia);
        }
        return $query;
    }

    // Scope para filtrar por localidad
    public function scopePorLocalidad($query, $localidad)
    {
        if ($localidad) {
            return $query->where('localidad', $localidad);
        }
        return $query;
    }

    // Obtener provincias únicas
    public static function getProvincias()
    {
        return self::activos()->distinct()->pluck('provincia')->sort();
    }

    // Obtener localidades por provincia
    public static function getLocalidadesPorProvincia($provincia)
    {
        return self::activos()->where('provincia', $provincia)->distinct()->pluck('localidad')->sort();
    }
}
