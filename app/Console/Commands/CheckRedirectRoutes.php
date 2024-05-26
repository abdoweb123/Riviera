<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use ReflectionClass;

class CheckRedirectRoutes extends Command
{
    protected $signature = 'routes:check-redirects';

    protected $description = 'Check if all redirect routes in controllers (including Modules) exist';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $definedRoutes = collect(Route::getRoutes()->getRoutesByName())->keys();
        $modulesDirectory = base_path('Modules');
        foreach (File::directories($modulesDirectory) as $moduleDirectory) {
            foreach (File::allFiles($moduleDirectory.'/Http/Controllers') as $file) {
                $controllerNamespace = 'Modules\\'.basename($moduleDirectory).'\\Http\\Controllers\\'.$file->getBasename('.php');
                $reflection = new ReflectionClass($controllerNamespace);

                foreach ($reflection->getMethods() as $method) {
                    $methodCode = File::get($file->getPathname());
                    $methodName = $method->name;

                    if (preg_match("/->redirect\(['\"]([^\s'\"()]+)['\"]\)/", $methodCode, $matches)) {
                        $routeName = $matches[1];

                        if (! $definedRoutes->contains($routeName)) {
                            $this->error("Route '$routeName' in method '$methodName' of controller '$controllerNamespace' does not exist.");
                        }
                    }
                }
            }
        }

        $this->info('Route validation complete.');
    }
}
