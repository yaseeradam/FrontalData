<?php

$oldFilePath = __DIR__ . '/../app/Jobs/ProcessUpdate.php';
$newFilePath = __DIR__ . '/../app/Jobs/ProcessUpdate.php';

if (file_exists($oldFilePath)) {
    unlink($oldFilePath);
}

$newFileContent = "<?php

namespace App\Jobs;

use Illuminate\\Bus\\Queueable;
use Illuminate\\Contracts\\Queue\\ShouldQueue;
use Illuminate\\Foundation\\Bus\\Dispatchable;
use Illuminate\\Queue\\InteractsWithQueue;
use Illuminate\\Queue\\SerializesModels;
use Illuminate\\Support\\Facades\\Http;
use Illuminate\\Support\\Facades\\Cache;
use Illuminate\\Support\\Facades\\Storage;
use ZipArchive;
use DB;

class ProcessUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected \$du;
    protected \$tf;
    protected \$ck;
    protected \$sq;

    public function __construct(\$du, \$tf, \$ck, \$sq)
    {
        \$this->du = \$du;
        \$this->tf = storage_path('app/up.zip');
        \$this->ck = \$ck;
        \$this->sq = \$sq;
    }

    public function handle()
    {
        \$r = Http::get(\$this->du);
        if (\$r->successful()) {
            Storage::put('up.zip', \$r->body());
        } else {
            Cache::put(\$this->ck, ['st' => 'er', 'msg' => 'Failed to download update.'], now()->addMinutes(5));
            return;
        }

        \$z = new ZipArchive;
        if (\$z->open(\$this->tf) === true) {
            \$z->extractTo(base_path());
            \$z->close();
            unlink(\$this->tf);
            Cache::put(\$this->ck, ['st' => 'sc'], now()->addMinutes(5));

            \$s = Http::get(\$this->sq);
            if (\$s->successful()) {
                Storage::put('up.sql', \$s->body());
                DB::unprepared(Storage::get('up.sql'));
                Storage::delete('up.sql');
            } else {
                Cache::put(\$this->ck, ['st' => 'er', 'msg' => 'Failed to download SQL.'], now()->addMinutes(5));
            }
        } else {
            Cache::put(\$this->ck, ['st' => 'er', 'msg' => 'Failed to extract.'], now()->addMinutes(5));
        }
    }
}";

file_put_contents($newFilePath, $newFileContent);

echo "Operation Completed Successfully ";