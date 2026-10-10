<?php

namespace App\Console\Commands;

use App\Models\Premium;
use App\Models\Reflection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncAudioStorage extends Command
{
    protected $signature = 'media:sync-audio-storage';
    protected $description = 'Move premium audio to private disk and free audio to public disk';

    public function handle(): int
    {
        $this->syncModel(Premium::query()->select(['id', 'audio_file', 'premium']), 'premiums');
        $this->syncModel(Reflection::query()->select(['id', 'audio_file', 'premium']), 'reflections');

        return self::SUCCESS;
    }

    protected function syncModel($query, string $label): void
    {
        $movedToPrivate = 0;
        $movedToPublic = 0;

        $query->chunkById(200, function ($items) use (&$movedToPrivate, &$movedToPublic) {
            foreach ($items as $item) {
                $path = (string) ($item->audio_file ?? '');
                if ($path === '') {
                    continue;
                }

                $shouldBePrivate = (bool) $item->premium;
                $fromDisk = $shouldBePrivate ? 'public' : 'local';
                $toDisk = $shouldBePrivate ? 'local' : 'public';

                if (Storage::disk($toDisk)->exists($path)) {
                    if (Storage::disk($fromDisk)->exists($path)) {
                        Storage::disk($fromDisk)->delete($path);
                    }
                    continue;
                }

                if (!Storage::disk($fromDisk)->exists($path)) {
                    continue;
                }

                $read = Storage::disk($fromDisk)->readStream($path);
                if (!$read) {
                    continue;
                }

                Storage::disk($toDisk)->writeStream($path, $read);
                if (is_resource($read)) {
                    fclose($read);
                }
                Storage::disk($fromDisk)->delete($path);

                if ($shouldBePrivate) {
                    $movedToPrivate++;
                } else {
                    $movedToPublic++;
                }
            }
        });

        $this->line($label . ': moved_to_private=' . $movedToPrivate . ' moved_to_public=' . $movedToPublic);
    }
}

