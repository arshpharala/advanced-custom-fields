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

if (!function_exists('have_rows')) {
    function have_rows(string $key, Model $model = null): bool
    {
        $model = $model ?: app('acf.current_model');
        return $model && method_exists($model, 'haveRows') ? $model->haveRows($key) : false;
    }
}

if (!function_exists('the_row')) {
    function the_row(string $key, Model $model = null): array
    {
        $model = $model ?: app('acf.current_model');
        return $model && method_exists($model, 'theRow') ? $model->theRow($key) : [];
    }
}

if (!function_exists('get_sub_field')) {
    function get_sub_field(string $key, Model $model = null)
    {
        $model = $model ?: app('acf.current_model');
        return $model && method_exists($model, 'getSubField') ? $model->getSubField($key) : null;
    }
}

if (!function_exists('the_sub_field')) {
    function the_sub_field(string $key, Model $model = null)
    {
        echo get_sub_field($key, $model);
    }
}
