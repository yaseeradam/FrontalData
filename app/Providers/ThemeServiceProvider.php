<?php

namespace App\Providers;

use App\View\Theme;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class ThemeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->extend('view.finder', function ($service, $app) {
            $paths = $app['config']['view.paths'];

            // Theme path depends on the current request. Skip in console/installer
            // contexts where no HTTP request has been captured yet — otherwise
            // resolving view.finder during boot triggers a Request facade lookup
            // before the request singleton exists.
            if ($app->bound('request') && !$app->runningInConsole()) {
                try {
                    $currentRoute = Request::path();
                    if (!str_starts_with($currentRoute, 'admin')) {
                        $theme = jannah_opt('preferred_theme', 'default');
                        $themePath = base_path("resources/themes/{$theme}");
                        if (!in_array($themePath, $paths)) {
                            array_unshift($paths, $themePath);
                        }
                    }
                } catch (\Throwable $e) {
                    // Request not available yet — fall back to plain view paths.
                }
            }

            foreach (glob(base_path('app/Plugins/*'), GLOB_ONLYDIR) as $pluginPath) {
                $pluginViews = "{$pluginPath}/views";
                if (is_dir($pluginViews) && !in_array($pluginViews, $paths)) {
                    $paths[] = $pluginViews;
                }
            }

            return new Theme($app['files'], $paths);
        });
    }

    public function boot()
    {
        // Plugin view namespaces don't depend on the request — safe to register always.
        foreach (glob(base_path('app/Plugins/*'), GLOB_ONLYDIR) as $pluginPath) {
            $pluginViews = "{$pluginPath}/views";
            if (is_dir($pluginViews)) {
                $pluginSlug = basename($pluginPath);
                View::addNamespace("plugins.{$pluginSlug}", $pluginViews);
            }
        }

        // Theme namespace depends on the current request. Skip in console/installer
        // contexts where no HTTP request has been captured yet.
        if ($this->app->bound('request') && !$this->app->runningInConsole()) {
            $currentRoute = Request::path();
            if (!str_starts_with($currentRoute, 'admin')) {
                $theme = jannah_opt('preferred_theme', 'default');
                $themeViewPath = resource_path("resources/themes/{$theme}");
                View::addNamespace('theme', $themeViewPath);
            }
        }

        View::composer('*', function ($view) {
            try {
                app('view.finder')->find($view->getName());
            } catch (\InvalidArgumentException $e) {
                // View not found in current finder — let Laravel surface it.
            }
        });
    }
}