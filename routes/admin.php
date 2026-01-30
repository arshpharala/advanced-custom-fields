<?php

use Illuminate\Support\Facades\Route;
use Arshpharala\AdvancedCustomFields\Http\Controllers\Admin\FieldGroupController;
use Arshpharala\AdvancedCustomFields\Http\Controllers\Admin\FieldController;
use Arshpharala\AdvancedCustomFields\Http\Controllers\Admin\LocationController;
use Arshpharala\AdvancedCustomFields\Http\Controllers\Admin\ImportExportController;
use Arshpharala\AdvancedCustomFields\Http\Controllers\Admin\HealthController;

Route::prefix(config('advanced-custom-fields.route_prefix', 'admin/advanced-custom-fields'))
    ->middleware(config('advanced-custom-fields.middleware', ['web', 'auth']))
    ->as('acf.admin.')
    ->group(function () {
        Route::get('/', [FieldGroupController::class, 'index'])->name('index');
        Route::resource('groups', FieldGroupController::class);
        
        Route::post('/fields', [FieldController::class, 'store'])->name('fields.store');
        Route::put('/fields/{field}', [FieldController::class, 'update'])->name('fields.update');
        Route::post('/fields/sort', [FieldController::class, 'sort'])->name('fields.sort');
        Route::delete('/fields/{field}', [FieldController::class, 'destroy'])->name('fields.destroy');
        
        Route::get('import-export', [ImportExportController::class, 'index'])->name('import-export.index');
        Route::post('export', [ImportExportController::class, 'export'])->name('export');
        Route::post('import', [ImportExportController::class, 'import'])->name('import');
        
        Route::get('health', [HealthController::class, 'index'])->name('health');
    });
