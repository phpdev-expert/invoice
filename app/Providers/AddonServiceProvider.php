<?php

namespace FI\Providers;

use FI\Modules\Addons\Repositories\AddonRepository;
use FI\Support\Directory;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(AddonRepository $addonRepository, Request $request)
    {
        if ($request->segment(1) !== 'setup' and (!app()->runningInConsole() or $this->app->environment('testing')))
        {
            config(['fi.menus.navigation' => []]);
            config(['fi.menus.system' => []]);

            $addons = $addonRepository->getEnabled();

            foreach ($addons as $addon)
            {
                config(['fi.menus.navigation.' . $addon->id => $addon->navigation_menu]);
                config(['fi.menus.system.' . $addon->id => $addon->system_menu]);
            }

            $addonDirectories = Directory::listDirectories(base_path('custom/addons'));

            // Scan addon directories for routes, views and language files.
            foreach ($addonDirectories as $addonDirectory)
            {
                $routesPath = addon_path($addonDirectory . '/routes.php');
                $viewsPath  = addon_path($addonDirectory . '/Views');
                $langPath   = addon_path($addonDirectory . '/Lang');

                if (file_exists($routesPath))
                {
                    require $routesPath;
                }

                if (file_exists($viewsPath))
                {
                    $this->app->view->addLocation($viewsPath);
                }

                if (file_exists($langPath))
                {
                    $this->loadTranslationsFrom($langPath, 'addon');
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
