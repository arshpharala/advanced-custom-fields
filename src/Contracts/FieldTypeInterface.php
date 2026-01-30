<?php

namespace Arshpharala\AdvancedCustomFields\Contracts;

use Arshpharala\AdvancedCustomFields\Models\Field;

interface FieldTypeInterface
{
    /**
     * Get the unique name of the field type.
     */
    public function getName(): string;

    /**
     * Render the field input for the admin UI.
     */
    public function renderInput(Field $field, $value = null, string $inputName = null): string;

    /**
     * Validate the input value.
     */
    public function validate($value, Field $field): array;

    /**
     * Format the value for storage.
     */
    public function formatValue($value, Field $field);

    /**
     * Prepare the value for display/usage.
     */
    public function prepareValue($value, Field $field);
}
