<?php
namespace NexaMerchant\Apis\Console\Commands;

use Nicelizhi\Apps\Console\Commands\CommandInterface;

class Install extends CommandInterface 

{
    protected $signature = 'Apis:install';

    protected $description = 'Install Apis an app';

    public function getAppVer() {
        return config("Apis.ver");
    }

    public function getAppName() {
        return config("Apis.name");
    }

    public function handle()
    {
        $this->info("Install app: Apis");
        if (!$this->confirm('Do you wish to continue?')) {
            // ...
            $this->error("App Apis Install cannelled");
            return false;
        }
    }
}