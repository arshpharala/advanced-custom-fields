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
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'default_value' => 'json',
        'options' => 'json',
        'conditional_logic' => 'json',
        'presentation' => 'json',
        'order' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class, 'group_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(Value::class, 'field_id');
    }
}
