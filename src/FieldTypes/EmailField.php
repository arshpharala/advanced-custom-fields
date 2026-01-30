<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;

class EmailField extends BaseFieldType
{
    public function getName(): string
    {
        return 'email';
    }
}
