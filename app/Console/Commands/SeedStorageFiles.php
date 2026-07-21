<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedStorageFiles extends Command
{
    protected $signature = 'storage:seed';
    protected $description = 'Copy seed images from public/img/ to storage/app/public/ (non-overwrite)';

    public function handle()
    {
        $dirs = ['profiles', 'attachments'];

        foreach ($dirs as $dir) {
            $source = public_path("img/{$dir}");
            $dest = storage_path("app/public/{$dir}");

            if (!is_dir($source)) {
                $this->warn("Source not found: {$source}");
                continue;
            }

            if (!is_dir($dest)) {
                mkdir($dest, 0755, true);
            }

            $files = glob("{$source}/*");
            $copied = 0;

            foreach ($files as $file) {
                $basename = basename($file);
                $target = "{$dest}/{$basename}";

                if (!file_exists($target)) {
                    copy($file, $target);
                    $copied++;
                }
            }

            $this->info("{$dir}: {$copied} file(s) copied, " . count($files) . " total in source");
        }

        return 0;
    }
}
