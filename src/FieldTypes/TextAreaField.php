<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;

class TextAreaField extends BaseFieldType
{
    public function getName(): string
    {
        return 'textarea';
    }
}
