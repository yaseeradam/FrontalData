<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\PluginService;

class PluginServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PluginService::class, function ($app) {
            return new PluginService();
        });
    }

    public function boot()
    {
        // Plugin metadata lives in the DB. If the DB isn't reachable yet
        // (fresh install, migrations not run, broken creds) we silently skip
        // plugin loading instead of crashing the entire framework boot.
        try {
            $pluginService = $this->app->make(PluginService::class);
            $plugins = $pluginService->getPlugins();
        } catch (\Throwable $e) {
            return;
        }

        foreach ($plugins as $plugin) {
            if (!empty($plugin['status']) && $plugin['status'] === 'active') {
                $this->loadPlugin($plugin['slug']);
            }
        }
    }

    protected function loadPlugin(string $slug): void
    {
        $pluginNamespace = "App\\Plugins\\{$slug}\\";
        $pluginPath = app_path("Plugins/{$slug}");
        $pluginClass = $pluginNamespace . ucfirst($slug);

        if (class_exists($pluginClass)) {
            $instance = app($pluginClass);
            if (method_exists($instance, 'boot')) {
                $instance->boot();
            }
        }

        $this->registerRoutes($pluginPath);
        $this->registerViews($slug, $pluginPath);
    }

    protected function registerRoutes(string $pluginPath): void
    {
        $webRoutes = "{$pluginPath}/routes/web.php";
        $apiRoutes = "{$pluginPath}/routes/api.php";

        $this->app->booted(function () use ($webRoutes, $apiRoutes) {
            if (file_exists($webRoutes)) {
                Route::middleware('web')->group($webRoutes);
            }
            if (file_exists($apiRoutes)) {
                Route::prefix('api')->middleware('api')->group($apiRoutes);
            }
        });
    }

    protected function registerViews($slug, $pluginPath)
    {
        $pluginViewPath = "{$pluginPath}/views";
        $themeViewPath = resource_path('themes/default');
        $adminViewPath = resource_path('views');
    
        $viewFinder = $this->app['view.finder'];
        
        $themeHasMainLayout = is_file("{$themeViewPath}/layouts/app.blade.php");
    
        if (is_dir($pluginViewPath)) {
            $this->loadViewsFrom($pluginViewPath, "plugins." . strtolower($slug));
            $viewFinder->addLocation($pluginViewPath);
        }
        
        if (is_dir($themeViewPath)) {
            $this->loadViewsFrom($themeViewPath, "theme");
            $viewFinder->addLocation($themeViewPath);
            if ($themeHasMainLayout) {
                $this->loadViewComponentsAs("theme", $this->getThemeComponents($themeViewPath));
            }
        }
        
        if (is_dir($adminViewPath)) {
            $this->loadViewsFrom($adminViewPath, "admin");
            $viewFinder->addLocation($adminViewPath);
            if (!$themeHasMainLayout) {
                $this->loadViewComponentsAs("admin", $this->getAdminComponents($adminViewPath));
            }
        }
    
    }
    
    protected function getThemeComponents($themePath)
    {
        $components = [];
        $componentPath = "{$themePath}/components";
        $files = glob("{$componentPath}/*.blade.php");
    
        foreach ($files as $file) {
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $components[$fileName] = "App\\View\\Themes\\Components\\" . ucfirst($fileName);
        }
    
        return $components;
    }
    
    protected function getAdminComponents($viewPath)
    {
        $components = [];
        $componentPath = "{$viewPath}/components";
        $files = glob("{$componentPath}/*.blade.php");
    
        foreach ($files as $file) {
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $components[$fileName] = "App\\View\\Components\\" . ucfirst($fileName);
        }
        
        return $components;
    }

}
