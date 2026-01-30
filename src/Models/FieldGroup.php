<?php

namespace Arshpharala\AdvancedCustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FieldGroup extends Model
{
    use SoftDeletes;

    protected $table = 'acf_field_groups';

    protected $fillable = [
        'name',
        'key',
        'description',
        'position',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class, 'group_id')->orderBy('order');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'group_id')->orderBy('order');
    }
}
