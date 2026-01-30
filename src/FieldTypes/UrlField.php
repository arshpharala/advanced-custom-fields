<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;

class UrlField extends BaseFieldType
{
    public function getName(): string
    {
        return 'url';
    }
}
