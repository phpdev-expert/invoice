<?php

namespace FI\Providers;

use FI\Support\Directory;
use Illuminate\Support\ServiceProvider;

class DashboardWidgetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $widgets = Directory::listContents(__DIR__ . '/../Widgets/Dashboard');

        foreach ($widgets as $widget)
        {
            $widgetPath = app_path() . '/Widgets/Dashboard/' . $widget;

            if (file_exists($widgetPath . '/Views'))
            {
                view()->addLocation($widgetPath . '/Views');

                $views = Directory::listContents($widgetPath . '/Views');

                foreach ($views as $view)
                {
                    $composerFile = str_replace('.blade.php', '', $view) . '.php';

                    if (file_exists($widgetPath . '/' . $composerFile))
                    {
                        view()->composer(str_replace('.blade.php', '', $view), 'FI\Widgets\Dashboard\\' . $widget . '\\' . str_replace('.php', '', $composerFile));
                    }
                }
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
