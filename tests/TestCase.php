<?php

namespace Arshpharala\AdvancedCustomFields\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Arshpharala\AdvancedCustomFields\AdvancedCustomFieldsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            AdvancedCustomFieldsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        
        // Run migrations
        $migration = include __DIR__ . '/../database/migrations/2024_01_30_000000_create_acf_tables.php';
        $migration->up();
        
        $migrationParent = include __DIR__ . '/../database/migrations/2024_01_30_000001_add_parent_id_to_acf_fields.php';
        $migrationParent->up();
    }
}
