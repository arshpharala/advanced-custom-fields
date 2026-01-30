<?php

namespace Arshpharala\AdvancedCustomFields\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Arshpharala\AdvancedCustomFields\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ImportExportController extends Controller
{
    public function index()
    {
        $lastExportHash = Setting::where('key', 'last_export_hash')->first()?->value;
        $exportPath = config('advanced-custom-fields.export_path');
        $fileExists = File::exists($exportPath);
        
        $currentDefinitions = \Arshpharala\AdvancedCustomFields\Models\FieldGroup::with(['fields', 'locations'])->get();
        $currentHash = md5(json_encode($currentDefinitions));
        
        $mismatch = $fileExists && ($lastExportHash !== $currentHash);

        return view('acf::admin.import-export.index', compact('lastExportHash', 'mismatch', 'exportPath', 'fileExists'));
    }

    public function export()
    {
        Artisan::call('acf:export');
        return redirect()->back()->with('success', 'Definitions exported successfully.');
    }

    public function import(Request $request)
    {
        Artisan::call('acf:import');
        return redirect()->back()->with('success', 'Definitions imported successfully.');
    }
}
