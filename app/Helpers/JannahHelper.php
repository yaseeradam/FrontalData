<?php

use App\Models\JannahOption;
use Illuminate\Support\Facades\Cache;

if (!function_exists('jannah_opt')) {
    function jannah_opt($key, $default = null)
    {
        $options = Cache::remember('jannah_options', 43200, function () {
            return get_all_opts();
        });
        return $options[$key] ?? $default;
    }
}

if (!function_exists('add_opt')) {
    function add_opt($key, $value)
    {
        $value = is_array($value) ? serialize($value) : $value;
        $result = JannahOption::create(['opt_key' => $key, 'opt_value' => $value]);
        Cache::forget('jannah_options');
        return $result;
    }
}

if (!function_exists('update_opt')) {
    function update_opt($key, $value)
    {
        $option = JannahOption::where('opt_key', $key)->first();
        if ($option) {
            $option->opt_value = is_array($value) ? serialize($value) : $value;
            $result = $option->save();
            Cache::forget('jannah_options');
            return $result;
        }
        return false;
    }
}

if (!function_exists('delete_opt')) {
    function delete_opt($key)
    {
        $option = JannahOption::where('opt_key', $key)->first();
        if ($option) {
            $result = $option->delete();
            Cache::forget('jannah_options');
            return $result;
        }
        return false;
    }
}

if (!function_exists('basecdn')) {
    function basecdn($path)
    {
        // Serve assets from this app's own URL (APP_URL / ASSET_URL) instead of a
        // hardcoded external CDN, so the app is portable across domains/rebrands.
        return asset(ltrim($path, '/'));
    }
}

if (!function_exists('is_serialized')) {
    function is_serialized($data)
    {
        return $data === 'b:0;' || @unserialize($data) !== false;
    }
}

if (!function_exists('get_all_opts')) {
    function get_all_opts()
    {
        $options = JannahOption::all();
        $result = [];
        foreach ($options as $option) {
            $value = $option->opt_value;
            $result[$option->opt_key] = is_serialized($value) ? unserialize($value) : $value;
        }
        return $result;
    }
}

if (!function_exists('getThemeMeta')) {
    function getThemeMeta($themeName)
    {
        $themePath = resource_path("themes/{$themeName}/theme.json");
        if (file_exists($themePath)) {
            return json_decode(file_get_contents($themePath), true);
        }
        return null;
    }
}

if (!function_exists('getPreferredTheme')) {
    function getPreferredTheme()
    {
        return jannah_opt('preferred_theme', 'default');
    }
}

if (!function_exists('activateTheme')) {
    function activateTheme($theme)
    {
        return update_opt('preferred_theme', $theme);
    }
}

if (!function_exists('getAllThemes')) {
    function getAllThemes()
    {
        $themePath = resource_path('themes/');
        $themes = [];
        foreach (glob($themePath . '*', GLOB_ONLYDIR) as $themeDir) {
            $themeName = basename($themeDir);
            $themeMeta = getThemeMeta($themeName);
            if ($themeMeta) {
                $themes[] = [
                    'name' => $themeMeta['name'],
                    'slug' => $themeMeta['slug'],
                    'image' => $themeMeta['image'],
                    'url' => $themeMeta['url'],
                    'version' => $themeMeta['version'],
                    'developer' => $themeMeta['developer'],
                    'description' => $themeMeta['description'],
                ];
            }
        }
        return $themes;
    }
}
