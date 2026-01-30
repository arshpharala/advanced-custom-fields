<?php

namespace Arshpharala\AdvancedCustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Value extends Model
{
    protected $table = 'acf_values';

    protected $fillable = [
        'model_type',
        'model_id',
        'field_id',
        'locale',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
