<?php

namespace Arshpharala\AdvancedCustomFields;

use Illuminate\Support\ServiceProvider;
use Arshpharala\AdvancedCustomFields\Commands\InstallCommand;
use Arshpharala\AdvancedCustomFields\Commands\ExportCommand;
use Arshpharala\AdvancedCustomFields\Commands\ImportCommand;
use Illuminate\Support\Facades\Blade;

class AdvancedCustomFieldsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/advanced-custom-fields.php', 'advanced-custom-fields'
        );

        $this->app->singleton(\Arshpharala\AdvancedCustomFields\Support\FieldTypeRegistry::class, function () {
            $registry = new \Arshpharala\AdvancedCustomFields\Support\FieldTypeRegistry();
            
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\TextField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\TextAreaField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\SelectField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\ToggleField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\NumberField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\DateField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\EmailField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\UrlField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\ColorField());
            $registry->register(new \Arshpharala\AdvancedCustomFields\FieldTypes\RepeaterField());
            
            return $registry;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish Configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/advanced-custom-fields.php' => config_path('advanced-custom-fields.php'),
            ], 'acf-config');

            // Publish Migrations
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            // Register Commands
            $this->commands([
                InstallCommand::class,
                ExportCommand::class,
                ImportCommand::class,
            ]);
        }

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'acf');

        // Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');

        // Register Blade Directives
        $this->registerBladeDirectives();

        // Register Blade Components
        $this->registerBladeComponents();
    }

    /**
     * Register Blade Directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('acf', function ($expression) {
            return "<?php echo acf({$expression}); ?>";
        });
    }

    /**
     * Register Blade Components.
     */
    protected function registerBladeComponents(): void
    {
        Blade::componentNamespace('Arshpharala\\AdvancedCustomFields\\View\\Components', 'acf');
    }
}
