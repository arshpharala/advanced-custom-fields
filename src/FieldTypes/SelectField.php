<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;
use Arshpharala\AdvancedCustomFields\Models\Field;

class SelectField extends BaseFieldType
{
    public function getName(): string
    {
        return 'select';
    }

    public function validate($value, Field $field): array
    {
        $rules = parent::validate($value, $field);
        $choices = $field->options['choices'] ?? [];
        
        if (isset($field->options['multiple']) && $field->options['multiple']) {
            $rules[$field->key][] = 'array';
        }

        return $rules;
    }
}
