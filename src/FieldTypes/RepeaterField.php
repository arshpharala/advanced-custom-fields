<?php

namespace Arshpharala\AdvancedCustomFields\FieldTypes;

use Arshpharala\AdvancedCustomFields\Support\BaseFieldType;
use Arshpharala\AdvancedCustomFields\Models\Field;

class RepeaterField extends BaseFieldType
{
    public function getName(): string
    {
        return 'repeater';
    }

    public function render($field, $model = null)
    {
        $value = $model ? $model->acf($field->key) : ($field->default_value ?? []);
        $subFields = $field->subFields;

        return view('acf::types.repeater', compact('field', 'value', 'subFields', 'model'))->render();
    }

    public function formatValue($value, $field)
    {
        // Recursively format sub-fields
        if (!is_array($value)) {
            return [];
        }

        $formatted = [];
        $subFields = $field->subFields;

        foreach ($value as $row) {
            $formattedRow = [];
            foreach ($subFields as $subField) {
                $fieldType = app(\Arshpharala\AdvancedCustomFields\Support\FieldTypeRegistry::class)->get($subField->type);
                $subValue = $row[$subField->key] ?? null;
                $formattedRow[$subField->key] = $fieldType ? $fieldType->formatValue($subValue, $subField) : $subValue;
            }
            $formattedRow['__row_index'] = $row['__row_index'] ?? null;
            $formatted[] = $formattedRow;
        }

        return $formatted;
    }
}
