<?php

if (!function_exists('basecdn')) {
    function basecdn($path) {
        // Serve assets from this app's own URL instead of a hardcoded external CDN.
        return asset(ltrim($path, '/'));
    }
}
