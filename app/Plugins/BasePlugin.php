<?php

namespace App\Plugins;

abstract class BasePlugin
{
    protected $pluginName;

    public function __construct($pluginName)
    {
        $this->pluginName = $pluginName;
        $this->boot(); 
    }

    abstract public function activate();
    abstract public function deactivate();

    public function boot()
    {
        // run
    }
}
