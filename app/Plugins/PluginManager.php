<?php

namespace App\Plugins;

class PluginManager
{
    protected $plugins = [];

    public function register(BasePlugin $plugin)
    {
        $this->plugins[$plugin->pluginName] = $plugin;
    }

    public function activateAll()
    {
        foreach ($this->plugins as $plugin) {
            $plugin->activate();
        }
    }

    public function deactivateAll()
    {
        foreach ($this->plugins as $plugin) {
            $plugin->deactivate();
        }
    }
}
