<?php

namespace Arshpharala\AdvancedCustomFields\Commands;

use Illuminate\Console\Command;
use Arshpharala\AdvancedCustomFields\Models\FieldGroup;
use Arshpharala\AdvancedCustomFields\Models\Field;
use Arshpharala\AdvancedCustomFields\Models\Location;
use Arshpharala\AdvancedCustomFields\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ImportCommand extends Command
{
    protected $signature = 'acf:import {--path= : Custom import path}';
    protected $description = 'Import ACF definitions from JSON.';

    public function handle()
    {
        $path = $this->option('path') ?: config('advanced-custom-fields.export_path');

        if (!File::exists($path)) {
            $this->error("Export file not found at: {$path}");
            return 1;
        }

        $data = json_decode(File::get($path), true);

        DB::transaction(function () use ($data) {
            foreach ($data as $groupData) {
                $fields = $groupData['fields'];
                $locations = $groupData['locations'];
                unset($groupData['fields'], $groupData['locations']);

                $group = FieldGroup::updateOrCreate(['key' => $groupData['key']], $groupData);

                // Import Fields
                foreach ($fields as $fieldData) {
                    Field::updateOrCreate(
                        ['key' => $fieldData['key']],
                        array_merge($fieldData, ['group_id' => $group->id])
                    );
                }

                // Import Locations (Simple approach: replace all)
                $group->locations()->delete();
                foreach ($locations as $locationData) {
                    $group->locations()->create($locationData);
                }
            }
        });

        // Update hash
        $hash = md5(File::get($path));
        Setting::updateOrCreate(['key' => 'last_export_hash'], ['value' => $hash]);

        $this->info("ACF definitions imported successfully.");
        
        return 0;
    }
}
