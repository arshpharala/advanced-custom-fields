<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;
use Arshpharala\AdvancedCustomFields\Models\Field;

class ToggleField extends BaseFieldType
{
    public function getName(): string
    {
        return 'toggle';
    }

    public function formatValue($value, Field $field)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
