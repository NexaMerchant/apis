<?php
namespace NexaMerchant\Apis\Console\Commands;

use NexaMerchant\Apps\Console\Commands\CommandInterface;

class UnInstall extends CommandInterface 

{
    protected $signature = 'Apis:uninstall';

    protected $description = 'Uninstall Apis an app';

    public function getAppVer() {
        return config("Apis.ver");
    }

    public function getAppName() {
        return config("Apis.name");
    }

    public function handle()
    {
        if (!$this->confirm('Do you wish to continue?')) {
            // ...
            $this->error("App Apis UnInstall cannelled");
            return false;
        }
    }
}