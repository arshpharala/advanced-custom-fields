<?php

namespace Arshpharala\AdvancedCustomFields\View\Components;

use Illuminate\View\Component;
use Arshpharala\AdvancedCustomFields\Models\FieldGroup;
use Illuminate\Database\Eloquent\Model;

class Form extends Component
{
    public ?Model $model;
    public string $context;
    public string $position;
    public $groups;

    public function __construct(?Model $model = null, string $context = 'admin.edit', string $position = 'normal')
    {
        $this->model = $model;
        $this->context = $context;
        $this->position = $position;
        
        $this->groups = $this->resolveGroups();
    }

    protected function resolveGroups()
    {
        // Simple resolver for now
        $query = FieldGroup::where('is_active', true)
            ->where('position', $this->position)
            ->with(['fields' => fn($q) => $q->where('is_active', true)]);

        if ($this->model) {
            $query->whereHas('locations', function($q) {
                $q->where('model_type', get_class($this->model))
                  ->where('context', $this->context);
            });
        }

        return $query->orderBy('order')->get();
    }

    public function render()
    {
        return view('acf::components.form');
    }
}
