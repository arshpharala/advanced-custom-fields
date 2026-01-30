<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;

class NumberField extends BaseFieldType
{
    public function getName(): string
    {
        return 'number';
    }
}
