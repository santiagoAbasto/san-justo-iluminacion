<?php

if (!function_exists('getLocalizedField')) {
    function getLocalizedField($object, $field)
    {
        $locale = app()->getLocale();

        if ($locale === 'es') {
            return $object->{$field} ?? '';
        }

        $localizedField = $field . ($locale === 'en' ? 'en' : 'port');

        if (isset($object->{$localizedField}) && !empty($object->{$localizedField})) {
            return $object->{$localizedField};
        }

        return $object->{$field} ?? '';
    }
}
