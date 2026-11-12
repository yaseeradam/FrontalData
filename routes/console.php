<?php

use App\Plugins\VtuNaijaPriceSync\Services\SyncService;
use App\Services\UpdatePublisher;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('vtunaija:check-price-changes {--type=* : Limit the check to data, cabletv, or electricity}', function () {
    $noticeTitle = 'VTUNAIJA price changes detected';

    try {
        $pluginActive = DB::table('plugins')
            ->where('slug', 'VtuNaijaPriceSync')
            ->where('status', 'active')
            ->exists();
    } catch (\Throwable $e) {
        $this->warn('VTUNAIJA price check could not verify plugin status: ' . $e->getMessage());

        return 1;
    }

    if (!$pluginActive) {
        try {
            DB::table('notifications')
                ->where('title', $noticeTitle)
                ->update([
                    'status' => 'inactive',
                    'date' => now(),
                ]);
        } catch (\Throwable $e) {
            // The notifications table may not exist during early installation.
        }

        $this->info('VTUNAIJA price check skipped because the plugin is inactive.');

        return 0;
    }

    $summary = (new SyncService())->checkForChanges((array) $this->option('type'));

    if ($summary['changes'] > 0) {
        $typeSummary = collect($summary['runs'])
            ->filter(fn ($run) => (($run['created'] ?? 0) + ($run['updated'] ?? 0)) > 0)
            ->map(fn ($run) => "{$run['product_type']}: {$run['created']} new, {$run['updated']} changed")
            ->implode('; ');
        $failureMessage = $summary['ok'] ? '' : ' Some checks failed: ' . implode(' ', $summary['messages']);

        DB::table('notifications')->updateOrInsert(
            ['title' => $noticeTitle],
            [
                'admin_id' => 1,
                'message' => "Daily VTUNAIJA check found {$summary['changes']} price change(s). {$typeSummary}{$failureMessage}",
                'action_url' => url('/admin/vtunaija-price-sync'),
                'status' => 'active',
                'date' => now(),
            ]
        );

        $this->info("VTUNAIJA price check found {$summary['changes']} change(s).");

        return $summary['ok'] ? 0 : 1;
    }

    if (!$summary['ok']) {
        $message = implode(' ', $summary['messages']);
        DB::table('notifications')->updateOrInsert(
            ['title' => $noticeTitle],
            [
                'admin_id' => 1,
                'message' => $message ?: 'The latest VTUNAIJA daily check could not complete.',
                'action_url' => url('/admin/vtunaija-price-sync'),
                'status' => 'active',
                'date' => now(),
            ]
        );

        $this->warn($message ?: 'VTUNAIJA price check could not complete.');

        return 1;
    }

    DB::table('notifications')
        ->where('title', $noticeTitle)
        ->update([
            'message' => 'The latest VTUNAIJA daily check did not find price changes.',
            'status' => 'inactive',
            'date' => now(),
        ]);

    $this->info('VTUNAIJA price check completed. No changes found.');

    return 0;
})->purpose('Check VTUNAIJA catalogs for price changes and notify the admin');

Schedule::command('vtunaija:check-price-changes')->dailyAt('06:00')->withoutOverlapping();

Artisan::command('updates:publish
    {--package= : Specific update ZIP to upload. Defaults to the package named in the manifest download URL.}
    {--manifest= : Manifest JSON path.}
    {--bookmark= : Cyberduck .duck bookmark path.}
    {--host= : FTP host override.}
    {--username= : FTP username override.}
    {--port= : FTP port override.}
    {--remote-path= : Remote FTP directory.}
    {--timeout= : FTP connection timeout in seconds.}
    {--ssl : Use FTPS instead of plain FTP.}
    {--no-passive : Disable FTP passive mode.}
    {--dry-run : Validate config, manifest, and checksum without uploading.}', function () {
    $password = config('services.jannah_updates.ftp.password');

    if (!$password && !$this->option('dry-run')) {
        $password = $this->secret('FTP password');
    }

    $options = array_filter([
        'package' => $this->option('package'),
        'manifest' => $this->option('manifest') ?: 'storage/update-packages/manifest.json',
        'bookmark' => $this->option('bookmark'),
        'host' => $this->option('host'),
        'username' => $this->option('username'),
        'port' => $this->option('port'),
        'remote_path' => $this->option('remote-path'),
        'timeout' => $this->option('timeout'),
        'ssl' => $this->option('ssl') ? true : null,
        'passive' => $this->option('no-passive') ? false : null,
        'password' => $password,
        'dry_run' => (bool) $this->option('dry-run'),
    ], fn ($value) => $value !== null && $value !== '');

    try {
        $result = app(UpdatePublisher::class)->publish($options);
    } catch (\Throwable $e) {
        $this->error($e->getMessage());

        return 1;
    }

    $this->info($result['dry_run'] ? 'Update publish dry run passed.' : 'Update package uploaded successfully.');
    $this->line("Version: {$result['version']}");
    $this->line("SHA256: {$result['sha256']}");
    $this->line("FTP: {$result['ftp']['username']}@{$result['ftp']['host']}:{$result['ftp']['port']}");

    foreach ($result['files'] as $file) {
        $this->line(basename($file['local']) . " -> {$file['remote']}");
    }

    return 0;
})->purpose('Upload the current hosted update package and manifest to the FTP update site');
