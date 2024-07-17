<?php
namespace NexaMerchant\Apis\Console\Commands;
use Illuminate\Support\Facades\Artisan;

use Nicelizhi\Apps\Console\Commands\CommandInterface;

class GenerateApiDocs extends CommandInterface 

{
    protected $signature = 'Apis:gendocs';

    protected $description = 'Generate l5-swagger docs.';

    public function getAppVer() {
        return config("Apis.ver");
    }

    public function getAppName() {
        return config("Apis.name");
    }

    public function handle()
    {
        $this->info("Generate Docs");
        if (!$this->confirm('Do you wish to continue?')) {
            // ...
            $this->error("Generate Docs cannelled");
            return false;
        }
        
        $this->warn('Step: Generate l5-swagger docs...');
        $result = shell_exec('php artisan l5-swagger:generate --all');
        $this->info($result);

        $this->comment('-----------------------------');
        $this->comment('Success: NexaMerchant REST API has been configured successfully.');
    }
}