<?php

namespace Arshpharala\AdvancedCustomFields\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Arshpharala\AdvancedCustomFields\Models\Value;
use Arshpharala\AdvancedCustomFields\Models\Field;

class HealthController extends Controller
{
    public function index()
    {
        // Detect orphan values (fields no longer exist)
        $orphans = Value::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('acf_fields')
                  ->whereRaw('acf_fields.id = acf_values.field_id');
        })->count();

        return view('acf::admin.health.index', compact('orphans'));
    }
}
