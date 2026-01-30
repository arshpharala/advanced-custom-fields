<?php

namespace Arshpharala\AdvancedCustomFields\Commands;

use Illuminate\Console\Command;
use Arshpharala\AdvancedCustomFields\Models\FieldGroup;
use Arshpharala\AdvancedCustomFields\Models\Setting;
use Illuminate\Support\Facades\File;

class ExportCommand extends Command
{
    protected $signature = 'acf:export {--path= : Custom export path}';
    protected $description = 'Export ACF definitions to JSON.';

    public function handle()
    {
        $path = $this->option('path') ?: config('advanced-custom-fields.export_path');
        
        $groups = FieldGroup::with(['fields', 'locations'])->get();
        
        $data = $groups->map(function ($group) {
            return [
                'name' => $group->name,
                'key' => $group->key,
                'description' => $group->description,
                'position' => $group->position,
                'order' => $group->order,
                'is_active' => $group->is_active,
                'fields' => $group->fields->map(fn($f) => $f->makeHidden(['id', 'group_id', 'created_at', 'updated_at', 'deleted_at'])),
                'locations' => $group->locations->map(fn($l) => $l->makeHidden(['id', 'group_id', 'created_at', 'updated_at'])),
            ];
        });

        $json = json_encode($data, JSON_PRETTY_PRINT);
        
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $json);

        // Update hash
        $hash = md5($json);
        Setting::updateOrCreate(['key' => 'last_export_hash'], ['value' => $hash]);

        $this->info("ACF definitions exported to: {$path}");
        
        return 0;
    }
}
