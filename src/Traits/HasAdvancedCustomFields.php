<?php

namespace Arshpharala\AdvancedCustomFields\Traits;

use Arshpharala\AdvancedCustomFields\Models\Field;
use Arshpharala\AdvancedCustomFields\Models\Value;
use Arshpharala\AdvancedCustomFields\Models\FieldGroup;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

trait HasAdvancedCustomFields
{
    /** @var array State for currently looping repeaters */
    protected $acfRepeaterStates = [];

    /**
     * Get all ACF values for this model.
     */
    public function acfValues(): MorphMany
    {
        return $this->morphMany(Value::class, 'model');
    }

    /**
     * Get a custom field value.
     */
    public function acf(string $key, $default = null)
    {
        $field = $this->getCachedField($key);

        if (!$field) {
            return $default;
        }

        $valueModel = $this->acfValues()
            ->where('field_id', $field->id)
            ->where('locale', app()->getLocale())
            ->first();

        if (!$valueModel && config('advanced-custom-fields.localization.fallback_to_default', true)) {
            $valueModel = $this->acfValues()
                ->where('field_id', $field->id)
                ->whereNull('locale')
                ->first();
        }

        $value = $valueModel ? $valueModel->value : ($field->default_value ?? $default);

        // If it's a repeater, ensure it's formatted (though formatValue in FieldType handles this)
        return $value;
    }

    /**
     * Check if a repeater has rows.
     */
    public function haveRows(string $key): bool
    {
        if (!isset($this->acfRepeaterStates[$key])) {
            $value = $this->acf($key);
            if (!is_array($value) || empty($value)) {
                return false;
            }
            $this->acfRepeaterStates[$key] = [
                'rows' => $value,
                'index' => -1,
                'count' => count($value),
            ];
        }

        $state = &$this->acfRepeaterStates[$key];
        return ($state['index'] + 1) < $state['count'];
    }

    /**
     * Advance the repeater row.
     */
    public function theRow(string $key): array
    {
        if (!isset($this->acfRepeaterStates[$key])) {
            $this->haveRows($key);
        }

        $state = &$this->acfRepeaterStates[$key];
        $state['index']++;

        return $state['rows'][$state['index']];
    }

    /**
     * Get a sub field value from the current repeater row.
     */
    public function getSubField(string $key, string $repeaterKey = null)
    {
        // If repeaterKey is not provided, try to guess from the last active state
        if (!$repeaterKey) {
            $repeaterKey = array_key_last($this->acfRepeaterStates);
        }

        if (!$repeaterKey || !isset($this->acfRepeaterStates[$repeaterKey])) {
            return null;
        }

        $state = $this->acfRepeaterStates[$repeaterKey];
        if ($state['index'] < 0 || $state['index'] >= $state['count']) {
            return null;
        }

        return $state['rows'][$state['index']][$key] ?? null;
    }

    /**
     * Set a custom field value.
     */
    public function setAcf(string $key, $value): self
    {
        $field = $this->getCachedField($key);

        if (!$field) {
            throw new \InvalidArgumentException("Field with key [{$key}] not found.");
        }

        $this->acfValues()->updateOrCreate(
            [
                'field_id' => $field->id,
                'locale' => config('advanced-custom-fields.localization.enabled') ? app()->getLocale() : null,
            ],
            ['value' => $value]
        );

        return $this;
    }

    /**
     * Get all custom field values as an array.
     */
    public function acfAll(): array
    {
        $groups = $this->acfGroups();
        $data = [];

        foreach ($groups as $group) {
            foreach ($group->fields as $field) {
                $data[$field->key] = $this->acf($field->key);
            }
        }

        return $data;
    }

    /**
     * Get field definition meta.
     */
    public function acfMeta(string $key)
    {
        return $this->getCachedField($key);
    }

    /**
     * Get all field groups applicable to this model.
     */
    public function acfGroups()
    {
        // This logic will be more complex later with Location rules
        return FieldGroup::whereHas('locations', function ($query) {
            $query->where('model_type', get_class($this));
        })->with('fields')->orderBy('order')->get();
    }

    /**
     * Internal helper to cache field lookups.
     */
    protected function getCachedField(string $key)
    {
        return Cache::remember("acf_field_{$key}", 3600, function () use ($key) {
            return Field::where('key', $key)->first();
        });
    }
}
