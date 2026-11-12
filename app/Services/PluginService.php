<?php

namespace App\Services;

use App\Models\Plugin;
use Illuminate\Support\Facades\File;

class PluginService
{
    
    protected $pluginPath;

    public function __construct()
    {
        $this->pluginPath = app_path('Plugins');
    }

    public function scanPlugins()
    {
        // Skip silently if the DB isn't ready (fresh install, missing table,
        // unreachable connection). The framework still boots; plugins just
        // won't be discovered until the DB is up.
        try {
            $pluginFolders = File::directories($this->pluginPath);

            foreach ($pluginFolders as $folder) {
                $slug = basename($folder);
                $metaFile = $folder . '/plugin.json';
                if (File::exists($metaFile)) {
                    $meta = json_decode(File::get($metaFile), true);

                    Plugin::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => $meta['name'] ?? $slug,
                            'version' => $meta['version'] ?? null,
                            'description' => $meta['description'] ?? null,
                            'developer' => $meta['developer'] ?? null,
                            'username' => $meta['username'] ?? null,
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            // DB unavailable — keep booting. Plugins will be (re)scanned later.
        }
    }

    public function getPlugins()
    {
        $this->scanPlugins();
        try {
            return Plugin::all();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    public function activatePlugin($slug)
    {
        $plugin = Plugin::where('slug', $slug)->first();
        if ($plugin && $plugin->status !== 'active') {
            $pluginClass = "App\\Plugins\\" . $slug . "\\" . ucfirst($slug);
            if (class_exists($pluginClass)) {
                $instance = new $pluginClass();
                $instance->activate();
            }
            $plugin->update(['status' => 'active']);
        }
    }
    
    public function deactivatePlugin($slug)
    {
        $plugin = Plugin::where('slug', $slug)->first();
        if ($plugin) {
            $pluginClass = "App\\Plugins\\" . $slug . "\\" . ucfirst($slug);
            if (class_exists($pluginClass)) {
                $instance = new $pluginClass();
                $instance->deactivate();
            }
            $plugin->update(['status' => 'inactive']);
        }
    }
    
    public function uninstallPlugin($slug)
    {
        $this->deactivatePlugin($slug);

        $plugin = Plugin::where('slug', $slug)->first();
        if ($plugin) {
            $pluginFolder = $this->pluginPath . '/' . $slug;
            if (File::exists($pluginFolder)) {
                File::deleteDirectory($pluginFolder);
            }
            $plugin->delete();
        }
    }
    
    public function installPluginFromUpload($uploadedFile)
    {
        $zipPath = $uploadedFile->getRealPath();
        $zip = new \ZipArchive();
        if ($zip->open($zipPath) === true) {
            $tempDir = storage_path('app/temp_plugin');
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            File::makeDirectory($tempDir, 0755, true);
            $zip->extractTo($tempDir);
            $zip->close();
            $folders = File::directories($tempDir);
            if (count($folders) > 0) {
                $pluginFolderName = basename($folders[0]);
                $destination = $this->pluginPath . '/' . $pluginFolderName;
                if (File::exists($destination)) {
                    throw new \Exception('Plugin already exists.');
                }
                File::move($folders[0], $destination);
                File::deleteDirectory($tempDir);
                $this->scanPlugins();
                return true;
            }
        }
        return false;
    }
}
