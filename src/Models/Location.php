<?php

namespace Arshpharala\AdvancedCustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    protected $table = 'acf_locations';

    protected $fillable = [
        'group_id',
        'model_type',
        'context',
        'position',
        'rules',
        'order',
    ];

    protected $casts = [
        'rules' => 'json',
        'order' => 'integer',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class, 'group_id');
    }
}
