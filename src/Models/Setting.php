<?php

namespace Arshpharala\AdvancedCustomFields\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'acf_settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];
}
