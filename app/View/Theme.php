<?php

namespace App\View;

use Illuminate\View\FileViewFinder;

class Theme extends FileViewFinder
{
    protected $theme;

    public function setTheme($theme)
    {
        $this->theme = $theme;
        $themePath = resource_path($theme);
        if (!in_array($themePath, $this->paths)) {
            array_unshift($this->paths, $themePath);
        }
    }

    public function find($view)
    {
        $themePath = resource_path("{$this->theme}/views/{$view}.blade.php");

        if (file_exists($themePath)) {
            return $themePath;
        }

        return parent::find($view);
    }
}
