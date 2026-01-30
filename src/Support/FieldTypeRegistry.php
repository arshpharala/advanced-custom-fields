<?php

namespace Arshpharala\AdvancedCustomFields\Support;

use Arshpharala\AdvancedCustomFields\Contracts\FieldTypeInterface;

class FieldTypeRegistry
{
    protected array $types = [];

    public function register(FieldTypeInterface $type): void
    {
        $this->types[$type->getName()] = $type;
    }

    public function get(string $name): ?FieldTypeInterface
    {
        return $this->types[$name] ?? null;
    }

    public function all(): array
    {
        return $this->types;
    }

    public function getNames(): array
    {
        return array_keys($this->types);
    }
}
