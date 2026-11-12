<?php

namespace App\Plugins\MyPlugin;

use App\Plugins\BasePlugin;
use App\Plugins\Hooks;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class MyPlugin extends BasePlugin
{
    /**
     * Constructor method for initializing the plugin.
     * Calls the parent constructor and boots the plugin.
     */
    public function __construct()
    {
        parent::__construct('MyPlugin');
        $this->boot();
    }
    
    /**
     * Bootstraps the plugin by loading routes and registering hooks.
     */
    public function boot()
    {
        $this->loadRoutes(); 
        Hooks::addAction('init', [$this, 'registerSidebar']);
        Hooks::addAction('init', [$this, 'debug']);
        Hooks::addAction('success_login', [$this, 'hasLogin']);
        Hooks::doAction('init');
    }

    /**
     * Registers sidebar menu items for the plugin in the admin panel.
     */
    public function registerSidebar()
    {
        $sidebarItems = Hooks::getSidebarItems();
        $newItems = [
            'sidebar' => [
                'title' => 'My Plugin Side',
                'route' => url('/admin/plugin-test'),
                'icon'  => 'jannah-cup',
                'subitems' => [
                    ['title' => 'Dashboard', 'route' => '/admin/plugins/myplugin/dashboard'],
                ]
            ],
            'userSidebar' => [
                'title' => 'Test Feature',
                'route' => url('/plugin-test'),
                'icon'  => 'jannah-cup',
            ],
            'more' => [
                'title' => 'My Plugin',
                'route' => '/admin/plugins',
                'icon'  => 'jannah-cup',
            ]
        ];
    
        foreach ($newItems as $hookType => $item) {
            if (!collect($sidebarItems)->contains('title', $item['title'])) {
                switch ($hookType) {
                    case 'sidebar':
                        Hooks::addSidebarItem($item);
                        break;
                    case 'userSidebar':
                        Hooks::addUserSidebarItem($item);
                        break;
                    case 'more':
                        Hooks::addMoreItem($item);
                        break;
                }
            }
        }
    
        $this->loadRoutes();
    }

    /**
     * Loads the plugin-specific routes from the routes file.
     */
    public function loadRoutes()
    {
        $pluginPath = base_path('app/Plugins/MyPlugin/routes/plugin.php');
        
        if (file_exists($pluginPath)) {
            require $pluginPath;
        } else {
            Log::debug('Plugin routes file not found: ' . $pluginPath);
        }
    }

    /**
     * Handles plugin activation logic.
     * This is typically used to set up initial configurations.
     */
    public function activate()
    {
        Log::debug('Plugin activated'); 
        $this->registerSidebar();
    }
    
    /**
     * Debug function to log when the plugin is running.
     */
    public function debug()
    {
        Log::debug('Plugin running');
    }
    
    /**
     * Hook function triggered on successful login.
     * Logs the login data for debugging or analytics.
     * 
     * @param array $data User login data passed by reference.
     */
    public function hasLogin(&$data)
    {
        Log::debug($data);
    }

    /**
     * Handles plugin deactivation logic.
     */
    public function deactivate()
    {
        Log::debug('Plugin deactivated'); 
    }
}
