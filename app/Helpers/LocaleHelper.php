<?php

namespace App\Helpers;

class LocaleHelper
{
    public static function getField($object, $field)
    {
        $locale = app()->getLocale();

        // Si es español (default), retorna el campo original
        if ($locale === 'es') {
            return $object->{$field} ?? '';
        }

        // Para otros idiomas, busca el campo con el sufijo correcto
        $localizedField = $field . ($locale === 'en' ? 'en' : 'port');

        // Si existe el campo localizado y no está vacío, lo retorna
        if (isset($object->{$localizedField}) && !empty($object->{$localizedField})) {
            return $object->{$localizedField};
        }

        // Fallback al campo original
        return $object->{$field} ?? '';
    }
}
