<?php

use Arshpharala\AdvancedCustomFields\Models\Field;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('acf')) {
    /**
     * Clear and easy way to get a custom field value from a model.
     */
    function acf(Model $model, string $key, $default = null)
    {
        if (!method_exists($model, 'acf')) {
            return $default;
        }

        return $model->acf($key, $default);
    }
}

if (!function_exists('acf_field')) {
    /**
     * Get the field definition.
     */
    function acf_field(string $key)
    {
        return Field::where('key', $key)->first();
    }
}
