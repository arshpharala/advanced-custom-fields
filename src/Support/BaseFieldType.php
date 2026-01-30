<?php

namespace Arshpharala\AdvancedCustomFields\Support;

use Arshpharala\AdvancedCustomFields\Contracts\FieldTypeInterface;
use Arshpharala\AdvancedCustomFields\Models\Field;
use Illuminate\Support\Facades\View;

abstract class BaseFieldType implements FieldTypeInterface
{
    abstract public function getName(): string;

    public function renderInput(Field $field, $value = null): string
    {
        return View::make("acf::types.{$this->getName()}", [
            'field' => $field,
            'value' => $value ?? $field->default_value,
            'presentation' => $field->presentation ?? [],
        ])->render();
    }

    public function validate($value, Field $field): array
    {
        $rules = [];
        if ($field->is_required) {
            $rules[] = 'required';
        }
        
        // Merge type-specific rules from field options
        if (isset($field->options['validation_rules'])) {
            $rules = array_merge($rules, (array) $field->options['validation_rules']);
        }

        return [$field->key => $rules];
    }

    public function formatValue($value, Field $field)
    {
        return $value;
    }

    public function prepareValue($value, Field $field)
    {
        return $value;
    }
}
