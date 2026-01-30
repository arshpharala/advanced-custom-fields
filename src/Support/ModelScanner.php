<?php

namespace Arshpharala\AdvancedCustomFields\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class ModelScanner
{
    /**
     * Scan the application for Eloquent models.
     */
    public static function all(): array
    {
        $models = [];
        $modelPath = app_path('Models');

        if (!File::exists($modelPath)) {
            return [];
        }

        $files = File::allFiles($modelPath);

        foreach ($files as $file) {
            $path = $file->getRelativePathname();
            $className = 'App\\Models\\' . str_replace(['/', '.php'], ['\\', ''], $path);

            if (class_exists($className) && is_subclass_of($className, Model::class)) {
                $models[] = $className;
            }
        }

        return $models;
    }
}
