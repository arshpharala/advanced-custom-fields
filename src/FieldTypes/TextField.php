<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;

class TextField extends BaseFieldType
{
    public function getName(): string
    {
        return 'text';
    }
}
