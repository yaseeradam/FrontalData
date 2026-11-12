<?php

namespace App\Plugins\VtuNaijaPriceSync;

use App\Plugins\Hooks;
use App\Plugins\VtuNaijaPriceSync\Services\Installer;
use Illuminate\Support\Facades\DB;

class VtuNaijaPriceSync
{
    private static bool $booted = false;

    public function boot(): void
    {
        if (self::$booted) {
            return;
        }

        self::$booted = true;

        Hooks::addSidebarItem([
            'title' => 'VTUNAIJA Sync',
            'route' => url('/admin/vtunaija-price-sync'),
            'icon' => 'ri-refresh-line',
        ]);
    }

    public function activate(): void
    {
        Installer::install();
        $this->boot();
    }

    public function deactivate(): void
    {
        self::$booted = false;

        try {
            DB::table('notifications')
                ->where('title', 'VTUNAIJA price changes detected')
                ->update([
                    'status' => 'inactive',
                    'date' => now(),
                ]);
        } catch (\Throwable $e) {
            // Deactivation should still succeed if the DB is not fully available.
        }
    }
}
