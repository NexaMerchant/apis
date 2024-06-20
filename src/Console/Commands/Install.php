<?php
namespace NexaMerchant\Apis\Console\Commands;
use Illuminate\Support\Facades\Artisan;

use Nicelizhi\Apps\Console\Commands\CommandInterface;

class Install extends CommandInterface 

{
    protected $signature = 'Apis:install';

    protected $description = 'Publish L5SwaggerServiceProvider provider, view and config files.';

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
        
        $this->warn('Step: Publishing L5Swagger Provider File...');
        // $result = Artisan::call("vendor:publish", [
        //     "--tag" => "bagisto-rest-api-swagger"
        // ]);
        $result = shell_exec('php artisan vendor:publish --tag=bagisto-rest-api-swagger');
        $this->info($result);

        $this->warn('Step: Generate l5-swagger docs (Admin & Shop)...');
        // $result =  Artisan::call("l5-swagger:generate", [
        //     "--all" => true
        // ]);
        $result = shell_exec('php artisan l5-swagger:generate --all');
        $this->info($result);

        $this->comment('-----------------------------');
        $this->comment('Success: Bagisto REST API has been configured successfully.');
    }
}