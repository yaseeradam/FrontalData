<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use ZipArchive;
use DB;

class ProcessUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $du;
    protected $tf;
    protected $ck;
    protected $sq;
    protected $options;

    public function __construct($du, $tf, $ck, $sq = null, array $options = [])
    {
        $this->du = $du;
        $this->tf = storage_path('app/up.zip');
        $this->ck = $ck;
        $this->sq = $sq;
        $this->options = $options;
    }

    public function handle()
    {
        Cache::put($this->ck, ['st' => 'pr', 'msg' => 'Downloading update package.'], now()->addMinutes(15));

        $r = Http::timeout(120)->get($this->du);
        if (!$r->successful()) {
            $this->failUpdate('Failed to download update.');
            return;
        }

        Storage::put('up.zip', $r->body());

        if (!$this->verifyFileHash($this->tf, $this->options['sha256'] ?? null)) {
            Storage::delete('up.zip');
            $this->failUpdate('Update package checksum does not match.');
            return;
        }

        $z = new ZipArchive;
        if ($z->open($this->tf) === true) {
            if (!$this->zipIsSafe($z)) {
                $z->close();
                Storage::delete('up.zip');
                $this->failUpdate('Update package contains unsafe paths.');
                return;
            }

            Cache::put($this->ck, ['st' => 'pr', 'msg' => 'Extracting update package.'], now()->addMinutes(15));
            $z->extractTo(base_path());
            $z->close();
            Storage::delete('up.zip');
            Cache::put($this->ck, ['st' => 'pr', 'msg' => 'Applying database changes.'], now()->addMinutes(15));

            if (($this->options['run_migrations'] ?? true) === true) {
                Artisan::call('migrate', ['--force' => true]);
            }

            if (!$this->applySql()) {
                return;
            }

            Artisan::call('optimize:clear');
            Cache::put($this->ck, ['st' => 'sc'], now()->addMinutes(5));
        } else {
            $this->failUpdate('Failed to extract update package.');
        }
    }

    private function applySql(): bool
    {
        if (!$this->sq) {
            return true;
        }

        $s = Http::timeout(60)->get($this->sq);
        if (!$s->successful()) {
            $this->failUpdate('Failed to download SQL.');
            return false;
        }

        Storage::put('up.sql', $s->body());

        if (!$this->verifyFileHash(storage_path('app/up.sql'), $this->options['sql_sha256'] ?? null)) {
            Storage::delete('up.sql');
            $this->failUpdate('SQL checksum does not match.');
            return false;
        }

        DB::unprepared(Storage::get('up.sql'));
        Storage::delete('up.sql');

        return true;
    }

    private function zipIsSafe(ZipArchive $zip): bool
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = str_replace('\\', '/', $zip->getNameIndex($i));

            if ($name === '' || Str::startsWith($name, ['/']) || str_contains($name, '../')) {
                return false;
            }

            foreach ($this->protectedPaths() as $protectedPath) {
                if ($name === $protectedPath || Str::startsWith($name, $protectedPath . '/')) {
                    return false;
                }
            }
        }

        return true;
    }

    private function protectedPaths(): array
    {
        return [
            '.env',
            'storage',
            'vendor',
            'node_modules',
            'bootstrap/cache',
            'public/install/global.php',
        ];
    }

    private function verifyFileHash(string $path, ?string $expectedHash): bool
    {
        if (!$expectedHash) {
            return true;
        }

        return hash_equals(strtolower($expectedHash), hash_file('sha256', $path));
    }

    private function failUpdate(string $message): void
    {
        Cache::put($this->ck, ['st' => 'er', 'msg' => $message], now()->addMinutes(5));
    }
}
