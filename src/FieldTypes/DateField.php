<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;

class DateField extends BaseFieldType
{
    public function getName(): string
    {
        return 'date';
    }
}
