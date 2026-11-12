<?php

namespace App\Plugins;

class Hooks
{
    protected static $actions = [];
    protected static $filters = [];
    protected static $sidebarItems = [];
    protected static $userSidebarItems = [];
    protected static $moreItems = [];

    public static function addAction(string $hook, callable $callback)
    {
        self::$actions[$hook][] = $callback;
    }

    public static function doAction(string $hook, &$data = null, ...$args)
    {
        if (!empty(self::$actions[$hook])) {
            foreach (self::$actions[$hook] as $callback) {
                call_user_func_array($callback, array_merge([&$data], $args));
            }
        }
    }

    public static function addFilter(string $hook, callable $callback)
    {
        self::$filters[$hook][] = $callback;
    }

    public static function applyFilters(string $hook, $value, ...$args)
    {
        if (!empty(self::$filters[$hook])) {
            foreach (self::$filters[$hook] as $callback) {
                $value = call_user_func_array($callback, array_merge([$value], $args));
            }
        }
        return $value;
    }
    
    public static function addSidebarItem(array $item)
    {
        self::$sidebarItems[] = $item;
    }

    public static function getSidebarItems()
    {
        return self::$sidebarItems;
    }
    
    public static function addUserSidebarItem(array $item)
    {
        self::$userSidebarItems[] = $item;
    }

    public static function getUserSidebarItems()
    {
        return self::$userSidebarItems;
    }
    
    public static function addMoreItem(array $item)
    {
        self::$moreItems[] = $item;
    }

    public static function getMoreItems()
    {
        return self::$moreItems;
    }
}
