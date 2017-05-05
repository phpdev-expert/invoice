<?php namespace FI\Providers;

use FI\Support\Directory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->environment('testing') and $this->app->config->get('app.key') == 'ReplaceThisWithYourOwnLicenseKey')
        {
            die('You must enter your license key in config/app.php to continue.');
        }

        $modules = Directory::listDirectories(app_path('Modules'));

        foreach ($modules as $module)
        {
            $routesPath = app_path('Modules/' . $module . '/routes.php');
            $viewsPath  = app_path('Modules/' . $module . '/Views');

            if (file_exists($routesPath))
            {
                require $routesPath;
            }

            if (file_exists($viewsPath))
            {
                $this->app->view->addLocation($viewsPath);
            }
        }

        foreach (File::allFiles(app_path('Helpers')) as $helper)
        {
            require_once $helper->getPathname();
        }

        $this->app->view->addLocation(base_path('custom/templates'));

        $this->app->register('FI\Providers\AddonServiceProvider');
        $this->app->register('FI\Providers\ComposerServiceProvider');
        $this->app->register('FI\Providers\ConfigServiceProvider');
        $this->app->register('FI\Providers\DashboardWidgetServiceProvider');
        $this->app->register('FI\Providers\EventServiceProvider');
        $this->app->register('Collective\Html\HtmlServiceProvider');
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
