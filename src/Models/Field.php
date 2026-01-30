<?php

namespace Arshpharala\AdvancedCustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use SoftDeletes;

    protected $table = 'acf_fields';

    protected $fillable = [
        'group_id',
        'name',
        'key',
        'type',
        'instructions',
        'is_required',
        'default_value',
        'options',
        'conditional_logic',
        'presentation',
        'order',
        'is_active',
        'parent_id',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'default_value' => 'json',
        'options' => 'array',
        'conditional_logic' => 'array',
        'presentation' => 'array',
        'order' => 'integer',
        'parent_id' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(FieldGroup::class, 'group_id');
    }

    public function parent()
    {
        return $this->belongsTo(Field::class, 'parent_id');
    }

    public function subFields()
    {
        return $this->hasMany(Field::class, 'parent_id')->orderBy('order');
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'field_id');
    }
}
