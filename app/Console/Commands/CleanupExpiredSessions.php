<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CleanupExpiredSessions extends Command
{
    protected $signature = 'session:cleanup';

    protected $description = 'Remove expired session files from storage';

    public function handle(): int
    {
        $sessionPath = storage_path('framework/sessions');

        if (! File::exists($sessionPath)) {
            $this->info('Session directory does not exist.');
            return self::SUCCESS;
        }

        $lifetime = config('session.lifetime', 120) * 60;
        $now = time();
        $deleted = 0;
        $remaining = 0;

        foreach (File::files($sessionPath) as $file) {
            if (! Str::startsWith($file->getFilename(), 'sess_')) {
                continue;
            }

            if (($now - $file->getMTime()) > $lifetime) {
                File::delete($file->getPathname());
                $deleted++;
            } else {
                $remaining++;
            }
        }

        $this->info("Session cleanup complete. Deleted: {$deleted}, Remaining: {$remaining}");
        return self::SUCCESS;
    }
}
