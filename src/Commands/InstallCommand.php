<?php

namespace Arshpharala\AdvancedCustomFields\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'acf:install';
    protected $description = 'Install Advanced Custom Fields package.';

    public function handle()
    {
        $this->info('Installing Advanced Custom Fields...');

        $this->call('vendor:publish', [
            '--provider' => 'Arshpharala\AdvancedCustomFields\AdvancedCustomFieldsServiceProvider',
            '--tag' => ['acf-config', 'acf-assets', 'acf-views']
        ]);

        $this->info('Running migrations...');
        $this->call('migrate');

        $this->info('ACF installed successfully!');
        
        if ($this->confirm('Would you like to import existing definitions if they exist?', true)) {
            $this->call('acf:import');
        }

        return 0;
    }
}
